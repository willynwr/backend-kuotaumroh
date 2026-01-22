<?php

namespace App\Services\Payment;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Bulk Payment Service
 * 
 * Handle bulk order dan payment untuk paket Umroh.
 * Proxies requests to external kuotaumroh.id API to avoid CORS issues.
 */
class BulkPaymentService
{
    protected QrisDynamicService $qrisService;

    /**
     * Base URL untuk external API
     */
    protected string $externalApiUrl = 'https://kuotaumroh.id/api';

    /**
     * Biaya platform default (dalam rupiah)
     */
    protected int $platformFee = 0;

    /**
     * Waktu expired pembayaran (dalam menit)
     */
    protected int $paymentExpiredMinutes = 15;

    public function __construct(QrisDynamicService $qrisService)
    {
        $this->qrisService = $qrisService;
        $this->platformFee = config('payment.platform_fee', 0);
        $this->paymentExpiredMinutes = config('payment.expired_minutes', 15);
        $this->externalApiUrl = config('payment.external_api_url', 'https://kuotaumroh.id/api');
    }

    /**
     * Get catalog paket dari external API
     * 
     * @param string $refCode Reference code untuk harga khusus
     * @return array
     */
    public function getCatalog(string $refCode = 'bulk_umroh'): array
    {
        try {
            // Proxy ke external API
            $response = Http::timeout(30)->get("{$this->externalApiUrl}/umroh/package", [
                'ref_code' => $refCode,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch catalog from external API', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            // Fallback ke local database jika external API gagal
            return $this->getCatalogFromLocal($refCode);
        } catch (\Exception $e) {
            Log::error('Exception fetching catalog: ' . $e->getMessage());
            return $this->getCatalogFromLocal($refCode);
        }
    }

    /**
     * Get catalog dari local database (fallback)
     */
    protected function getCatalogFromLocal(string $refCode = 'bulk_umroh'): array
    {
        $query = Produk::query();

        $products = $query->orderBy('nama_paket')->get();

        return $products->map(function ($product) use ($refCode) {
            $price = $refCode === 'bulk_umroh' 
                ? $product->harga_tp_travel 
                : $product->harga_eup;

            return [
                'id' => $this->generatePackageId($product),
                'name' => $product->nama_paket,
                'type' => $product->provider,
                'sub_type' => $product->tipe_paket,
                'days' => $product->masa_aktif,
                'quota' => $product->total_kuota,
                'telp' => $product->telp,
                'sms' => $product->sms,
                'bonus' => $product->kuota_bonus,
                'price_customer' => $product->harga_eup,
                'price_bulk' => $product->harga_tp_travel,
                'fee_affiliate' => 0,
                'is_active' => '1',
                'promo' => null,
            ];
        })->toArray();
    }

    /**
     * Generate package ID dari produk
     */
    protected function generatePackageId(Produk $product): string
    {
        // Format: R{masa_aktif}-{PROVIDER}-{ID}
        $provider = strtoupper(substr($product->provider ?? 'TSEL', 0, 4));
        return "R{$product->masa_aktif}-{$provider}-" . str_pad($product->id, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Parse package ID
     */
    public function parsePackageId(string $packageId): ?Produk
    {
        // Extract ID dari package_id (format: R{masa_aktif}-{PROVIDER}-{ID})
        $parts = explode('-', $packageId);
        if (count($parts) >= 3) {
            $id = intval($parts[2]);
            return Produk::find($id);
        }

        // Fallback: cari berdasarkan nama atau pattern lain
        return null;
    }

    /**
     * Create bulk payment order via external API
     * 
     * Proxies request ke kuotaumroh.id untuk menghindari CORS
     * 
     * @param array $data Request data
     * @return array Response dengan payment info
     */
    public function createBulkPayment(array $data): array
    {
        $batchId = $data['batch_id'] ?? $this->generateBatchId();
        $batchName = $data['batch_name'] ?? 'BULK_ORDER_' . date('YmdHis');
        $paymentMethod = strtoupper($data['payment_method'] ?? 'QRIS');
        $detail = $data['detail'] ?? null;
        $refCode = $data['ref_code'] ?? 'bulk_umroh';
        $msisdnList = $data['msisdn'] ?? [];
        $packageIdList = $data['package_id'] ?? [];

        // Validasi jumlah msisdn dan package_id harus sama
        if (count($msisdnList) !== count($packageIdList)) {
            throw new \InvalidArgumentException('Jumlah msisdn dan package_id harus sama');
        }

        if (empty($msisdnList)) {
            throw new \InvalidArgumentException('Minimal harus ada 1 nomor tujuan');
        }

        // Format msisdn ke 628xxx
        $formattedMsisdn = array_map(function ($msisdn) {
            return $this->formatMsisdn($msisdn);
        }, $msisdnList);

        // Prepare request body untuk external API
        // External API expects array format: msisdn[] and package_id[]
        $requestBody = [
            'batch_id' => $batchId,
            'batch_name' => $batchName,
            'payment_method' => $paymentMethod,
            'detail' => $detail,
            'ref_code' => $refCode,
            'msisdn' => $formattedMsisdn,
            'package_id' => $packageIdList,
        ];

        Log::info('Creating bulk payment via external API', [
            'request' => $requestBody,
            'url' => "{$this->externalApiUrl}/umroh/bulkpayment",
        ]);

        try {
            // Call external API
            $response = Http::timeout(60)
                ->asForm() // Send as form data for array support
                ->post("{$this->externalApiUrl}/umroh/bulkpayment", $this->buildFormData($requestBody));

            Log::info('External API response', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                // Store local record for tracking
                $this->storeLocalPaymentRecord($requestBody, $result);
                
                return [
                    'success' => true,
                    'message' => 'Bulk payment berhasil dibuat',
                    'data' => $this->mapExternalResponse($result, $batchId),
                ];
            }

            // Handle error response from external API
            $error = $response->json();
            throw new \Exception($error['message'] ?? 'Gagal membuat pembayaran: ' . $response->status());

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error to external API: ' . $e->getMessage());
            throw new \Exception('Tidak dapat terhubung ke server pembayaran. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error('Error creating bulk payment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Build form data for external API (supports array fields)
     */
    protected function buildFormData(array $data): array
    {
        $formData = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $formData["{$key}[]"] = $item;
                }
            } else {
                $formData[$key] = $value;
            }
        }
        
        return $data; // Laravel's Http::asForm handles arrays automatically
    }

    /**
     * Map response dari external API ke format internal
     */
    protected function mapExternalResponse(array $response, string $batchId): array
    {
        // Jika response langsung dari external API
        if (isset($response['id']) || isset($response['payment_id'])) {
            $paymentId = $response['payment_id'] ?? $response['id'] ?? null;
            
            return [
                'payment_id' => $paymentId,
                'batch_id' => $response['batch_id'] ?? $batchId,
                'batch_name' => $response['batch_name'] ?? null,
                'sub_total' => $response['sub_total'] ?? $response['subtotal'] ?? 0,
                'platform_fee' => $response['platform_fee'] ?? $response['biaya_platform'] ?? 0,
                'payment_unique' => $response['payment_unique'] ?? $response['unique_code'] ?? 0,
                'total_pembayaran' => $response['total_pembayaran'] ?? $response['total'] ?? 0,
                'metode_pembayaran' => $response['metode_pembayaran'] ?? $response['payment_method'] ?? 'QRIS',
                'status_pembayaran' => $response['status_pembayaran'] ?? $response['status'] ?? 'WAITING',
                'expired_at' => $response['expired_at'] ?? null,
                'remaining_time' => $response['remaining_time'] ?? (15 * 60),
                'payment_url' => $response['payment_url'] ?? ($paymentId ? "https://kuotaumroh.id/umroh/payment?id={$paymentId}" : null),
                'qris' => $this->extractQrisData($response),
                'items_count' => $response['items_count'] ?? 1,
            ];
        }
        
        // Jika response wrapped dalam data key
        if (isset($response['data'])) {
            return $this->mapExternalResponse($response['data'], $batchId);
        }

        return $response;
    }

    /**
     * Extract QRIS data dari response
     */
    protected function extractQrisData(array $response): ?array
    {
        // Check various possible QRIS data locations
        if (isset($response['qris'])) {
            return $response['qris'];
        }

        if (isset($response['qr_code_url']) || isset($response['qris_string'])) {
            return [
                'qris_string' => $response['qris_string'] ?? null,
                'qr_code_url' => $response['qr_code_url'] ?? null,
            ];
        }

        // Generate QR Code URL from payment ID if available
        if (isset($response['id']) || isset($response['payment_id'])) {
            $paymentId = $response['payment_id'] ?? $response['id'];
            return [
                'qris_string' => null,
                'qr_code_url' => "https://kuotaumroh.id/umroh/payment/qr?id={$paymentId}",
            ];
        }

        return null;
    }

    /**
     * Store local record for tracking purposes
     */
    protected function storeLocalPaymentRecord(array $request, array $response): void
    {
        try {
            // Optional: Store dalam tabel local untuk tracking
            // Tidak wajib, hanya untuk audit trail
            
            Log::info('Payment record stored', [
                'batch_id' => $request['batch_id'] ?? null,
                'ref_code' => $request['ref_code'] ?? null,
                'external_payment_id' => $response['id'] ?? $response['payment_id'] ?? null,
            ]);
        } catch (\Exception $e) {
            // Don't fail if local storage fails
            Log::warning('Failed to store local payment record: ' . $e->getMessage());
        }
    }

    /**
     * Get bulk payment history from external API
     */
    public function getHistory(string $agentId): array
    {
        try {
            $response = Http::timeout(30)->get("{$this->externalApiUrl}/umroh/bulkpayment", [
                'agent_id' => $agentId,
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? $response->json() ?? [];
            }

            Log::error('Failed to fetch history from external API', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('Exception fetching history: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get detail pesanan dari external API
     */
    public function getDetail(int $paymentId, string $agentId): array
    {
        try {
            $response = Http::timeout(30)->get("{$this->externalApiUrl}/umroh/bulkpayment/detail", [
                'id' => $paymentId,
                'agent_id' => $agentId,
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? $response->json() ?? [];
            }

            throw new \InvalidArgumentException('Data pembayaran tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Exception fetching detail: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get payment status from external API
     */
    public function getPaymentStatus(int $paymentId): array
    {
        try {
            $response = Http::timeout(30)->get("{$this->externalApiUrl}/umroh/payment/status", [
                'id' => $paymentId,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                // Map external status to internal format
                $data = $result['data'] ?? $result;
                $status = $data['status'] ?? $data['status_pembayaran'] ?? 'pending';
                
                // Normalize status
                $statusMap = [
                    'WAITING' => 'pending',
                    'VERIFY' => 'pending',
                    'SUCCESS' => 'success',
                    'FAILED' => 'failed',
                    'EXPIRED' => 'expired',
                ];
                
                $normalizedStatus = $statusMap[strtoupper($status)] ?? $status;

                return [
                    'success' => true,
                    'data' => [
                        'payment_id' => $data['payment_id'] ?? $data['id'] ?? $paymentId,
                        'batch_id' => $data['batch_id'] ?? null,
                        'status' => $normalizedStatus,
                        'status_pembayaran' => $data['status_pembayaran'] ?? $status,
                        'total_pembayaran' => $data['total_pembayaran'] ?? $data['total'] ?? 0,
                        'expired_at' => $data['expired_at'] ?? null,
                        'remaining_time' => $data['remaining_time'] ?? 0,
                        'qris' => $this->extractQrisData($data),
                    ],
                ];
            }

            return [
                'success' => false,
                'message' => 'Payment not found',
            ];
        } catch (\Exception $e) {
            Log::error('Exception fetching payment status: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment via external API
     */
    public function verifyPayment(int $paymentId, array $verificationData = []): array
    {
        try {
            $response = Http::timeout(30)->post("{$this->externalApiUrl}/umroh/payment/verify", [
                'id' => $paymentId,
                ...$verificationData,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'message' => 'Verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Exception verifying payment: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Mark payment as success (dipanggil setelah verifikasi QRIS berhasil)
     */
    public function markPaymentSuccess(int $paymentId, string $qrisRrn = null): bool
    {
        $pembayaran = Pembayaran::find($paymentId);

        if (!$pembayaran) {
            return false;
        }

        DB::beginTransaction();

        try {
            // Update pembayaran
            $pembayaran->markAsSuccess($qrisRrn, now());

            // TODO: Trigger aktivasi paket ke nomor tujuan
            // Untuk sekarang, update status pesanan ke 'proses'
            Pesanan::where('batch_id', $pembayaran->batch_id)
                ->update(['status_aktivasi' => Pesanan::STATUS_PROSES]);

            DB::commit();

            Log::info('Payment marked as success', [
                'payment_id' => $paymentId,
                'batch_id' => $pembayaran->batch_id,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark payment success: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate unique batch ID
     */
    protected function generateBatchId(): string
    {
        return 'BATCH_' . time() . mt_rand(100, 999);
    }

    /**
     * Format nomor telepon ke format 628xxx
     */
    protected function formatMsisdn(string $msisdn): ?string
    {
        // Hapus karakter non-digit
        $msisdn = preg_replace('/\D/', '', $msisdn);

        // Convert berbagai format ke 628xxx
        if (str_starts_with($msisdn, '08')) {
            $msisdn = '62' . substr($msisdn, 1);
        } elseif (str_starts_with($msisdn, '8')) {
            $msisdn = '62' . $msisdn;
        } elseif (str_starts_with($msisdn, '+62')) {
            $msisdn = substr($msisdn, 1);
        }

        // Validasi format 628xxx
        if (!preg_match('/^62[0-9]{9,12}$/', $msisdn)) {
            return null;
        }

        return $msisdn;
    }

    /**
     * Parse jadwal aktivasi dari detail string
     */
    protected function parseJadwalAktivasi(?string $detail): ?\DateTime
    {
        if (!$detail) {
            return now(); // Aktivasi langsung
        }

        // Parse format: {date: yyyy-mm-ddThh:mm}
        if (preg_match('/date:\s*([0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2})/', $detail, $matches)) {
            try {
                return new \DateTime($matches[1]);
            } catch (\Exception $e) {
                return now();
            }
        }

        return now();
    }
}
