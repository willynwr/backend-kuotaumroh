<?php

namespace App\Services\Payment;

use App\Models\Agent;
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
    protected string $externalApiUrl = 'https://tokodigi.id/api';

    /**
     * Base URL untuk tokodigi payment API
     */
    protected string $tokodigiApiUrl = 'https://tokodigi.id';

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
        $this->tokodigiApiUrl = config('payment.tokodigi_api_url', 'https://tokodigi.id');
    }

    /**
     * Get catalog paket dari local database
     * 
     * @deprecated Use PackagePricingService instead for pricing from VIEW
     * 
     * Method ini masih dipakai untuk backward compatibility (fallback),
     * tapi harga masih dari tabel Produk lokal, BUKAN dari VIEW kuotaumroh.
     * 
     * Untuk harga yang benar sesuai role (affiliate/agent/admin):
     * - Gunakan PackagePricingService->getBulkCatalogForAffiliate()
     * - Gunakan PackagePricingService->getBulkCatalogForAgent()
     * - Gunakan PackagePricingService->getBulkCatalogForAdmin()
     * - Gunakan PackagePricingService->getStoreCatalogForAgent()
     * 
     * @param string $refCode Reference code (ignored, semua harga sama)
     * @return array
     */
    public function getCatalog(string $refCode = 'bulk_umroh'): array
    {
        // Langsung ambil dari local database
        return $this->getCatalogFromLocal($refCode);
    }

    /**
     * Get catalog dari local database (fallback)
     * 
     * @deprecated Harga dari tabel Produk, bukan dari VIEW kuotaumroh
     */
    protected function getCatalogFromLocal(string $refCode = 'bulk_umroh'): array
    {
        $query = Produk::query();

        // Sort by promo existence (not null/empty first), then by name
        $products = $query->orderByRaw("CASE WHEN promo IS NOT NULL AND promo != '' THEN 0 ELSE 1 END")
                          ->orderBy('nama_paket')
                          ->get();

        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'package_id' => $product->id,
                'name' => $product->nama_paket,
                'packageName' => $product->nama_paket,
                'type' => $product->provider,
                'provider' => $product->provider,
                'sub_type' => $product->tipe_paket,
                'tipe_paket' => $product->tipe_paket,
                'days' => $product->masa_aktif,
                'masa_aktif' => $product->masa_aktif,
                'quota' => $product->total_kuota,
                'total_kuota' => $product->total_kuota,
                'telp' => $product->telp,
                'sms' => $product->sms,
                'bonus' => $product->kuota_bonus,
                'kuota_bonus' => $product->kuota_bonus,
                
                // Pricing Rules
                'price_app' => $product->harga_komersial, // Harga Coret
                'price_customer' => $product->price_customer, // Harga Customer (Non-Agent)
                'price_bulk' => $product->price_bulk, // Harga Agent
                
                // Legacy fields mapping for compatibility
                'price' => $product->price_customer, 
                'harga' => $product->price_customer,
                
                'fee_affiliate' => $product->fee_affiliate ?? 0,
                'is_active' => '1',
                'promo' => $product->promo,
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
     * PENTING: Harga diambil dari VIEW (server-side), BUKAN dari client input.
     * Jika ada _server_pricing, akan override price dari client.
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
        
        // Internal fields dari Controller
        $role = $data['_role'] ?? null;
        $userId = $data['_user_id'] ?? null;
        $serverPricing = $data['_server_pricing'] ?? null;

        // Validasi jumlah msisdn dan package_id harus sama
        if (count($msisdnList) !== count($packageIdList)) {
            throw new \InvalidArgumentException('Jumlah msisdn dan package_id harus sama');
        }

        if (empty($msisdnList)) {
            throw new \InvalidArgumentException('Minimal harus ada 1 nomor tujuan');
        }

        // ===== BUILD PRICE LIST (SERVER-SIDE) =====
        $priceList = [];
        $pricingDetails = []; // Untuk simpan ke local DB
        
        if ($serverPricing) {
            // Gunakan harga dari VIEW (server-side)
            foreach ($packageIdList as $index => $packageId) {
                if (!isset($serverPricing[$packageId])) {
                    throw new \InvalidArgumentException("Package {$packageId} tidak ditemukan atau tidak tersedia untuk user {$userId}");
                }
                
                $pricing = $serverPricing[$packageId];
                $priceList[] = $pricing['bulk_harga_beli']; // Harga yang dikirim ke external API
                
                // Simpan detail pricing per item (termasuk fee affiliate)
                $pricingDetails[] = [
                    'msisdn' => $msisdnList[$index],
                    'package_id' => $packageId,
                    'bulk_harga_beli' => $pricing['bulk_harga_beli'],
                    'bulk_harga_rekomendasi' => $pricing['bulk_harga_rekomendasi'],
                    'bulk_potensi_profit' => $pricing['bulk_potensi_profit'] ?? 0,
                    'bulk_final_fee_affiliate' => $pricing['bulk_final_fee_affiliate'] ?? 0, // Fee untuk affiliate
                ];
            }
            
            Log::info('✅ Using server-side pricing from VIEW', [
                'role' => $role,
                'user_id' => $userId,
                'price_count' => count($priceList),
            ]);
        } else {
            // Fallback: gunakan price dari client (backward compatibility)
            $priceList = $data['price'] ?? [];
            
            if (empty($priceList) || count($priceList) !== count($msisdnList)) {
                throw new \InvalidArgumentException('Price tidak tersedia. Sertakan affiliate_id atau agent_id untuk mendapatkan harga otomatis.');
            }
            
            Log::warning('⚠️  Using client-provided pricing (LEGACY - DEPRECATED)', [
                'price_count' => count($priceList),
            ]);
            
            // Build minimal pricing details untuk legacy
            foreach ($packageIdList as $index => $packageId) {
                $pricingDetails[] = [
                    'msisdn' => $msisdnList[$index],
                    'package_id' => $packageId,
                    'price' => $priceList[$index],
                    'source' => 'client_legacy',
                ];
            }
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
            'price' => $priceList, // Harga dari VIEW (server-side) atau fallback dari client
        ];

        Log::info('Creating bulk payment via external API', [
            'request' => $requestBody,
            'url' => "{$this->externalApiUrl}/umroh/bulkpayment",
            'pricing_source' => $serverPricing ? 'VIEW' : 'client_legacy',
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
                
                Log::info('✅ Bulk payment created successfully', [
                    'payment_id' => $result['payment_id'] ?? $result['id'] ?? 'unknown',
                    'has_qris' => isset($result['qris']),
                    'qris_structure' => isset($result['qris']) ? array_keys($result['qris']) : [],
                ]);
                
                // Store local record for tracking
                $this->storeLocalPaymentRecord($requestBody, $result, $pricingDetails, $role, $userId);
                
                $mappedResponse = $this->mapExternalResponse($result, $batchId);
                
                Log::info('📦 Mapped response', [
                    'qris' => $mappedResponse['qris'],
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Bulk payment berhasil dibuat',
                    'data' => $mappedResponse,
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
            
            // External API mengembalikan payment_amount sebagai total (harga + unique)
            $paymentAmount = (int) ($response['payment_amount'] ?? 0);
            $paymentUnique = (int) ($response['payment_unique'] ?? 0);
            $subTotal = $paymentAmount - $paymentUnique; // Hitung sub_total dari payment_amount - unique
            
            return [
                'payment_id' => $paymentId,
                'batch_id' => $response['batch_id'] ?? $batchId,
                'batch_name' => $response['batch_name'] ?? null,
                'sub_total' => $subTotal,
                'platform_fee' => $response['platform_fee'] ?? $response['biaya_platform'] ?? 0,
                'payment_unique' => $response['payment_unique'] ?? $response['unique_code'] ?? 0,
                'total_pembayaran' => $paymentAmount,
                'metode_pembayaran' => $response['metode_pembayaran'] ?? $response['payment_method'] ?? 'QRIS',
                'status_pembayaran' => $response['status_pembayaran'] ?? $response['payment_status'] ?? 'WAITING',
                'expired_at' => $response['expired_at'] ?? $response['payment_expired'] ?? null,
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
            $qris = $response['qris'];
            // Pastikan qr_code_url ada
            if (is_array($qris) && !isset($qris['qr_code_url']) && isset($response['payment_id'])) {
                $qris['qr_code_url'] = "https://kuotaumroh.id/umroh/payment?id={$response['payment_id']}";
            }
            return $qris;
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
                'qr_code_url' => "https://kuotaumroh.id/umroh/payment?id={$paymentId}",
            ];
        }

        return null;
    }

    /**
     * Store local record for tracking purposes dengan pricing details
     * 
     * @param array $request Request body yang dikirim ke external API
     * @param array $response Response dari external API
     * @param array $pricingDetails Pricing details dari VIEW (per item)
     * @param string|null $role Role user (affiliate/agent/admin)
     * @param string|null $userId User ID (AFTxxx/AGTxxx/ADMxxx)
     */
    protected function storeLocalPaymentRecord(
        array $request, 
        array $response,
        array $pricingDetails = [],
        ?string $role = null,
        ?string $userId = null
    ): void {
        try {
            Log::info('🔍 storeLocalPaymentRecord - Starting', [
                'response_keys' => array_keys($response),
                'request_keys' => array_keys($request),
                'pricing_details_count' => count($pricingDetails),
                'role' => $role,
                'user_id' => $userId,
            ]);
            
            // Ambil external payment ID
            $externalPaymentId = $response['id'] ?? $response['payment_id'] ?? null;
            
            if (!$externalPaymentId) {
                Log::warning('⚠️ No external payment ID found in response');
                return;
            }
            
            Log::info('✓ External payment ID found', ['id' => $externalPaymentId]);
            
            // Extract QRIS data dari response
            $qrisData = $response['qris'] ?? [];
            $qrisString = $qrisData['qris_string'] ?? $qrisData['string'] ?? null;
            $qrisNmid = $qrisData['nmid'] ?? null;
            
            // Enrich detail pesanan dengan nama paket dari database
            $msisdns = $request['msisdn'] ?? [];
            $packageIds = $request['package_id'] ?? [];
            $enrichedItems = [];
            
            Log::info('📦 Enriching items', [
                'msisdn_count' => count($msisdns),
                'package_id_count' => count($packageIds),
            ]);
            
            // Loop through each msisdn and get package info
            // Note: package_id dari external API (tokodigi) seperti 'R1-TSEL-000' 
            // tidak ada mapping langsung ke tabel produk lokal
            foreach ($msisdns as $index => $msisdn) {
                $packageId = $packageIds[$index] ?? null;
                
                // Simpan info dasar tanpa query ke tabel produk
                // karena external package_id tidak ada di database lokal
                $enrichedItems[] = [
                    'msisdn' => $msisdn,
                    'package_id' => $packageId,
                    'package_name' => $packageId, // Gunakan package_id sebagai nama
                ];
            }
            
            Log::info('✓ Items enriched', ['count' => count($enrichedItems)]);
            
            // ===== CALCULATE SUMMARY FROM PRICING DETAILS =====
            $totalHargaBeli = 0;
            $totalHargaRekomendasi = 0;
            $totalProfit = 0;
            $totalFeeTravel = 0;
            $totalFeeAffiliate = 0;
            $pricingSource = 'unknown';
            
            if (!empty($pricingDetails)) {
                // Detect pricing type: bulk atau store
                $firstItem = $pricingDetails[0] ?? [];
                
                if (isset($firstItem['bulk_harga_beli'])) {
                    // BULK PRICING
                    foreach ($pricingDetails as $item) {
                        $totalHargaBeli += (int) ($item['bulk_harga_beli'] ?? 0);
                        $totalHargaRekomendasi += (int) ($item['bulk_harga_rekomendasi'] ?? 0);
                        $totalProfit += (int) ($item['bulk_potensi_profit'] ?? 0);
                        // Ambil fee affiliate dari bulk pricing jika ada
                        $totalFeeAffiliate += (int) ($item['bulk_final_fee_affiliate'] ?? 0);
                    }
                    $pricingSource = 'VIEW_BULK';
                    
                    Log::info('💰 Bulk pricing summary calculated', [
                        'total_harga_beli' => $totalHargaBeli,
                        'total_harga_rekomendasi' => $totalHargaRekomendasi,
                        'total_profit' => $totalProfit,
                        'total_fee_affiliate' => $totalFeeAffiliate,
                    ]);
                    
                } elseif (isset($firstItem['toko_harga_jual'])) {
                    // STORE PRICING (INDIVIDUAL)
                    foreach ($pricingDetails as $item) {
                        $totalHargaBeli += (int) ($item['toko_harga_jual'] ?? 0); // Customer bayar
                        $totalHargaRekomendasi += (int) ($item['toko_harga_coret'] ?? 0); // Harga coret
                        $totalFeeTravel += (int) ($item['mandiri_final_fee_travel'] ?? 0);
                        $totalFeeAffiliate += (int) ($item['mandiri_final_fee_affiliate'] ?? 0);
                    }
                    $totalProfit = $totalFeeTravel; // Profit agent = fee travel
                    $pricingSource = 'VIEW_STORE';
                    
                    Log::info('💰 Store pricing summary calculated', [
                        'total_harga_jual' => $totalHargaBeli,
                        'total_harga_coret' => $totalHargaRekomendasi,
                        'total_fee_travel' => $totalFeeTravel,
                        'total_fee_affiliate' => $totalFeeAffiliate,
                    ]);
                }
            }
            
            // ===== CALCULATE AFFILIATE FEE BASED ON AGENT RELATIONSHIP =====
            // Cek apakah agent punya affiliate
            // Jika agent tidak punya affiliate_id, fee_affiliate = 0
            $feeAffiliate = 0;
            
            // Extract agent_id dari request
            $agentId = $userId ?? $request['agent_id'] ?? $request['affiliate_id'] ?? $request['ref_code'] ?? null;
            
            if ($agentId && $totalFeeAffiliate > 0) {
                // Query agent untuk cek apakah punya affiliate_id
                // Note: tabel agent punya kolom link_referal, bukan ref_code
                $agent = Agent::where('id', $agentId)
                    ->orWhere('link_referal', $agentId)
                    ->first();
                
                if ($agent && $agent->affiliate_id) {
                    // Agent punya affiliate, gunakan totalFeeAffiliate yang sudah dihitung
                    $feeAffiliate = $totalFeeAffiliate;
                    
                    Log::info('💳 Affiliate fee assigned', [
                        'agent_id' => $agent->id,
                        'affiliate_id' => $agent->affiliate_id,
                        'fee_affiliate' => $feeAffiliate,
                    ]);
                } else {
                    Log::info('⚠️ Agent has no affiliate, fee_affiliate = 0', [
                        'agent_id' => $agentId,
                        'has_affiliate' => isset($agent->affiliate_id),
                    ]);
                }
            }
            
            // Prepare data untuk create - sesuaikan dengan kolom tabel pembayaran
            // Kolom: id, external_payment_id, batch_id, batch_name, agent_id, produk_id, 
            //        msisdn, nama_paket, tipe_paket, harga_modal, harga_jual, profit, 
            //        metode_pembayaran, qris_string, qris_nmid, qris_rrn, total_pembayaran, 
            //        status_pembayaran, detail_pesanan, created_at, updated_at
            $externalStatusRaw = $response['payment_status'] ?? $response['status'] ?? null;
            $statusMap = [
                'INJECT' => Pembayaran::STATUS_SUCCESS,
                'SUCCESS' => Pembayaran::STATUS_SUCCESS,
                'SUKSES' => Pembayaran::STATUS_SUCCESS, // Added SUKSES
                'BERHASIL' => Pembayaran::STATUS_SUCCESS,
                'AKTIF' => Pembayaran::STATUS_SUCCESS,
                'VERIFY' => Pembayaran::STATUS_VERIFY,
                'WAITING' => Pembayaran::STATUS_WAITING,
                'PENDING' => Pembayaran::STATUS_WAITING,
                'FAILED' => Pembayaran::STATUS_FAILED,
                'EXPIRED' => Pembayaran::STATUS_EXPIRED,
                'CANCEL' => Pembayaran::STATUS_FAILED,
            ];
            $localStatus = $statusMap[strtoupper($externalStatusRaw ?? '')] ?? Pembayaran::STATUS_WAITING;

            $createData = [
                'external_payment_id' => (string) $externalPaymentId,
                'batch_id' => $request['batch_id'] ?? null,
                'batch_name' => $request['batch_name'] ?? null,
                'agent_id' => $userId ?? $request['agent_id'] ?? $request['affiliate_id'] ?? $request['ref_code'] ?? null,
                'produk_id' => json_encode($request['package_id'] ?? []), // Simpan array package_id
                'msisdn' => json_encode($request['msisdn'] ?? []),
                'nama_paket' => $response['package_name'] ?? $request['batch_name'] ?? 'Paket Data Umroh',
                'tipe_paket' => 'DATA', // Default value required by DB
                'metode_pembayaran' => $request['payment_method'] ?? 'QRIS',
                'qris_string' => $qrisString,
                'qris_nmid' => $qrisNmid,
                'total_pembayaran' => (int) ($response['payment_amount'] ?? 0),
                'status_pembayaran' => $localStatus,
                'detail_pesanan' => json_encode([
                    'msisdn' => $request['msisdn'] ?? [],
                    'package_id' => $request['package_id'] ?? [],
                    'detail' => $request['detail'] ?? null,
                    'items' => $enrichedItems,
                    'payment_expired' => $response['payment_expired'] ?? null,
                    'payment_unique' => $response['payment_unique'] ?? null,
                    'external_status' => $externalStatusRaw,
                    // ===== PRICING DETAILS FROM VIEW =====
                    'pricing_details' => $pricingDetails, // Per-item pricing dari VIEW (bulk atau store)
                    'pricing_source' => $pricingSource,
                    'role' => $role,
                    'user_id' => $userId,
                    'total_harga_beli' => $totalHargaBeli ?? null,
                    'total_harga_rekomendasi' => $totalHargaRekomendasi ?? null,
                    'total_profit' => $totalProfit ?? null,
                    'total_fee_travel' => $totalFeeTravel ?? null,
                    'total_fee_affiliate' => $totalFeeAffiliate ?? null,
                ]),
                // Fill required integer columns
                'harga_modal' => $totalHargaBeli ?? 0,
                'harga_jual' => $totalHargaRekomendasi ?? 0,
                'profit' => $totalProfit ?? 0,
                'fee_affiliate' => $feeAffiliate ?? 0, // Fee untuk affiliate jika agent punya affiliate
            ];
            
            Log::info('💾 Creating payment record', [
                'external_payment_id' => $createData['external_payment_id'],
                'batch_id' => $createData['batch_id'],
                'agent_id' => $createData['agent_id'],
            ]);
            
            // Simpan ke database lokal
            $payment = Pembayaran::create($createData);
            
            Log::info('✅ Payment record stored successfully', [
                'local_id' => $payment->id,
                'external_payment_id' => $externalPaymentId,
            ]);
        } catch (\Exception $e) {
            // Don't fail if local storage fails
            Log::error('❌ FAILED to store local payment record', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Create individual payment order via external API
     * 
     * Proxies request ke tokodigi.id untuk individual payment
     * 
     * PENTING: Harga diambil dari VIEW (server-side) untuk toko agent,
     * BUKAN dari client input.
     * 
     * @param array $data Request data
     * @return array Response dengan payment info
     */
    public function createIndividualPayment(array $data): array
    {
        $paymentMethod = strtoupper($data['payment_method'] ?? 'QRIS');
        $detail = $data['detail'] ?? null;
        $refCode = $data['ref_code'] ?? '0';
        $msisdn = $data['msisdn'] ?? null; // STRING untuk individual
        $packageId = $data['package_id'] ?? null; // STRING untuk individual
        
        // Internal fields dari Controller
        $storePricing = $data['_store_pricing'] ?? null;
        $agentId = $data['_agent_id'] ?? null;

        // Validasi
        if (!$msisdn || !$packageId) {
            throw new \InvalidArgumentException('msisdn dan package_id harus diisi');
        }

        // ===== BUILD PRICE (SERVER-SIDE) =====
        $price = 0;
        $pricingDetail = null;
        
        if ($storePricing) {
            // Gunakan harga dari VIEW (server-side) - TOKO AGENT
            $price = $storePricing['toko_harga_jual']; // Harga yang dibayar customer
            
            $pricingDetail = [
                'package_id' => $packageId,
                'toko_harga_coret' => $storePricing['toko_harga_coret'],
                'toko_harga_jual' => $storePricing['toko_harga_jual'],
                'toko_hemat' => $storePricing['toko_hemat'],
                'mandiri_final_fee_travel' => $storePricing['mandiri_final_fee_travel'],
                'mandiri_final_fee_affiliate' => $storePricing['mandiri_final_fee_affiliate'],
            ];
            
            Log::info('✅ Using server-side pricing from VIEW (STORE)', [
                'agent_id' => $agentId,
                'package_id' => $packageId,
                'toko_harga_jual' => $price,
                'fee_travel' => $storePricing['mandiri_final_fee_travel'],
                'fee_affiliate' => $storePricing['mandiri_final_fee_affiliate'],
            ]);
        } else {
            // Fallback: gunakan price dari client (backward compatibility - HOMEPAGE)
            $price = $data['price'] ?? 0;
            
            if (!$price || $price <= 0) {
                throw new \InvalidArgumentException('Price tidak tersedia. Sertakan agent_id untuk mendapatkan harga otomatis dari toko.');
            }
            
            Log::warning('⚠️ Using client-provided pricing (LEGACY - HOMEPAGE)', [
                'price' => $price,
                'ref_code' => $refCode,
            ]);
        }

        // Format msisdn ke 628xxx
        $formattedMsisdn = $this->formatMsisdn($msisdn);

        if (!$formattedMsisdn) {
            throw new \InvalidArgumentException('Format nomor telepon tidak valid');
        }

        // Prepare request body untuk external API
        $requestBody = [
            'payment_method' => $paymentMethod,
            'detail' => $detail,
            'ref_code' => $refCode,
            'msisdn' => $formattedMsisdn,
            'package_id' => $packageId,
            'price' => $price, // Harga dari VIEW (toko_harga_jual) atau fallback dari client
        ];

        Log::info('Creating individual payment via external API', [
            'request' => $requestBody,
            'url' => "{$this->externalApiUrl}/umroh/payment",
            'pricing_source' => $storePricing ? 'VIEW_STORE' : 'client_legacy',
        ]);

        try {
            // Call external API
            $response = Http::timeout(60)
                ->asForm()
                ->post("{$this->externalApiUrl}/umroh/payment", $requestBody);

            Log::info('External API response (individual)', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('✅ Individual payment created successfully', [
                    'payment_id' => $result['payment_id'] ?? $result['id'] ?? 'unknown',
                    'has_qris' => isset($result['qris']),
                ]);
                
                // Store local record for tracking (convert to array format)
                $this->storeLocalPaymentRecord([
                    'payment_method' => $paymentMethod,
                    'detail' => $detail,
                    'ref_code' => $refCode,
                    'msisdn' => [$formattedMsisdn], // Convert to array for consistency
                    'package_id' => [$packageId], // Convert to array for consistency
                    'batch_id' => 'IND-' . time() . mt_rand(100, 999), // Generate ID untuk individual (required by DB)
                    'batch_name' => 'Individual Order',
                ], $result, $pricingDetail ? [$pricingDetail] : [], 'agent', $agentId); // Pass store pricing details
                
                // Return response as-is dari external API
                return [
                    'success' => true,
                    'message' => 'Individual payment berhasil dibuat',
                    'data' => $result,
                ];
            }

            // Handle error response from external API
            $error = $response->json();
            $errorMessage = $error['message'] ?? 'Gagal membuat pembayaran: ' . $response->status();
            
            // Log detailed error
            Log::error('External API returned error', [
                'status' => $response->status(),
                'error_body' => $error,
                'request' => $requestBody,
            ]);
            
            // Provide more helpful error message
            if ($response->status() === 500 && str_contains($errorMessage, '00.00 WIB')) {
                throw new \Exception('Server pembayaran sedang mengalami kendala teknis. Ini biasanya terjadi karena limit transaksi harian telah tercapai. Silakan coba lagi setelah pukul 00.00 WIB atau hubungi admin.');
            }
            
            throw new \Exception($errorMessage);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Connection error to external API: ' . $e->getMessage());
            throw new \Exception('Tidak dapat terhubung ke server pembayaran. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error('Error creating individual payment: ' . $e->getMessage());
            throw $e;
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
     * Verify payment via tokodigi API and update local status if successful
     * API Endpoint: https://tokodigi.id/umroh/payment?id={payment_id}
     */
    public function verifyPayment(int $paymentId, array $verificationData = []): array
    {
        try {
            // Check payment status from tokodigi API (JSON endpoint)
            $url = rtrim($this->tokodigiApiUrl, '/') . '/api/umroh/payment';
            $response = Http::timeout(30)->get($url, [
                'id' => $paymentId,
            ]);

            Log::info('Tokodigi payment verification request', [
                'payment_id' => $paymentId,
                'url' => "{$url}?id={$paymentId}",
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Payment verification response from tokodigi API', [
                    'payment_id' => $paymentId,
                    'response' => $responseData,
                ]);
                
                // Handle various response formats from tokodigi API
                // Could be: {status: 'success'} or {data: {status: 'success'}} or [{status: 'success'}]
                $data = $responseData;
                if (isset($responseData['data'])) {
                    $data = $responseData['data'];
                }
                if (is_array($data) && isset($data[0])) {
                    $data = $data[0]; // If response is array, get first item
                }
                
                // Check if payment is successful on external API
                // Handle various status formats from tokodigi API
                $externalStatusRaw = $data['status'] 
                    ?? $data['payment_status'] 
                    ?? $data['status_pembayaran'] 
                    ?? $responseData['status'] 
                    ?? '';
                $externalStatus = strtoupper(trim((string) $externalStatusRaw));
                $mappedStatus = $this->mapExternalStatus($externalStatusRaw);
                
                Log::info('Parsed external status', [
                    'payment_id' => $paymentId,
                    'external_status_raw' => $externalStatusRaw,
                    'external_status' => $externalStatus,
                    'mapped_status' => $mappedStatus,
                    'parsed_data' => $data,
                ]);
                
                if ($mappedStatus === Pembayaran::STATUS_SUCCESS) {
                    // Update local database status
                    $updated = $this->markPaymentSuccess($paymentId, $data['qris_rrn'] ?? $data['rrn'] ?? null);
                    
                    if ($updated) {
                        Log::info('Local payment status updated to success', [
                            'payment_id' => $paymentId,
                        ]);
                        
                        return [
                            'success' => true,
                            'message' => 'Pembayaran berhasil diverifikasi',
                            'status' => 'berhasil',
                            'data' => $data,
                        ];
                    }
                    
                    // Payment was already marked as success before
                    return [
                        'success' => true,
                        'message' => 'Pembayaran sudah berhasil sebelumnya',
                        'status' => 'berhasil',
                        'data' => $data,
                    ];
                }

                if ($mappedStatus) {
                    $this->updateLocalPaymentStatus($paymentId, $mappedStatus);
                }
                
                // Payment not yet paid or still pending
                return [
                    'success' => true,
                    'message' => 'Status pembayaran: ' . ($externalStatus ?: 'menunggu pembayaran'),
                    'status' => $externalStatus ? strtolower($externalStatus) : 'pending',
                    'data' => $data,
                ];
            }

            Log::warning('Tokodigi API returned non-successful response', [
                'payment_id' => $paymentId,
                'status_code' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Verification failed - API returned error',
            ];
        } catch (\Exception $e) {
            Log::error('Exception verifying payment: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
                'exception' => $e->getTraceAsString(),
            ]);
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
        DB::beginTransaction();

        try {
            // Try to find by external_payment_id first (payment ID from external API)
            $pembayaran = Pembayaran::where('external_payment_id', (string) $paymentId)
                ->lockForUpdate()
                ->first();
            
            // If not found, try by local ID
            if (!$pembayaran) {
                $pembayaran = Pembayaran::lockForUpdate()->find($paymentId);
            }

            if (!$pembayaran) {
                DB::rollBack();
                Log::warning('Payment not found for marking success', [
                    'payment_id' => $paymentId,
                ]);
                return false;
            }

            // Cek apakah sudah SUCCESS/berhasil sebelumnya (double callback dari payment gateway)
            if (in_array(strtolower($pembayaran->status_pembayaran), ['success', 'berhasil'])) {
                DB::rollBack();
                Log::warning('Payment already marked as success', [
                    'payment_id' => $paymentId,
                    'batch_id' => $pembayaran->batch_id,
                ]);
                return true; // Return true karena payment memang sudah success
            }

            // Update pembayaran
            $pembayaran->markAsSuccess($qrisRrn, now());

            // TODO: Trigger aktivasi paket ke nomor tujuan
            // Untuk sekarang, update status pesanan ke 'proses'
            if ($pembayaran->batch_id) {
                Pesanan::where('batch_id', $pembayaran->batch_id)
                    ->update(['status_aktivasi' => Pesanan::STATUS_PROSES]);
            }

            DB::commit();

            Log::info('Payment marked as success', [
                'payment_id' => $paymentId,
                'external_payment_id' => $pembayaran->external_payment_id,
                'local_id' => $pembayaran->id,
                'batch_id' => $pembayaran->batch_id,
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark payment success: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
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

    /**
     * Get local payment detail from database
     * This returns data from the local database, not from external API
     * Use this for invoice where you need the updated status from local DB
     * 
     * @param string|int $paymentId - Can be external payment ID or local ID
     * @return array
     */
    public function getLocalPaymentDetail(string|int $paymentId): array
    {
        // Try to find by external_payment_id first (external ID from tokodigi)
        $payment = Pembayaran::where('external_payment_id', (string) $paymentId)->first();
        
        // If not found, try to find by local ID
        if (!$payment) {
            $payment = Pembayaran::find($paymentId);
        }
        
        if (!$payment) {
            throw new \InvalidArgumentException('Pembayaran tidak ditemukan');
        }

        // Jika status masih WAITING atau VERIFY, cek status terbaru dari external API
        if (in_array($payment->status_pembayaran, [Pembayaran::STATUS_WAITING, Pembayaran::STATUS_VERIFY])) {
            $externalId = $payment->external_payment_id ?? $paymentId;
            $externalStatus = $this->checkExternalPaymentStatus($externalId);
            
            if ($externalStatus && $externalStatus !== $payment->status_pembayaran) {
                // Update status di local DB
                $payment->status_pembayaran = $externalStatus;
                $payment->save();
                
                Log::info('📊 Payment status updated from external API', [
                    'payment_id' => $externalId,
                    'old_status' => $payment->getOriginal('status_pembayaran'),
                    'new_status' => $externalStatus,
                ]);
            }
        }

        // Parse detail_pesanan jika berupa JSON string
        $detailPesanan = $payment->detail_pesanan;
        if (is_string($detailPesanan)) {
            $detailPesanan = json_decode($detailPesanan, true);
        }

        // Ambil data agent untuk invoice (dari relasi atau agent_id)
        $agentName = 'Kuotaumroh.id';
        $agentPic = 'Kuotaumroh.id';
        $agentPhone = '+62 812-3456-7890';
        
        if ($payment->agent_id) {
            $agent = Agent::find($payment->agent_id);
            if ($agent) {
                $agentName = $agent->nama_travel ?? 'Kuotaumroh.id';
                $agentPic = $agent->nama_pic ?? 'Kuotaumroh.id';
                $agentPhone = $agent->no_hp ?? '+62 812-3456-7890';
            }
        }

        // Format response similar to external API but with local DB data
        return [
            'success' => true,
            'data' => [
                'payment_id' => $payment->external_payment_id ?? $payment->id,
                'id' => $payment->external_payment_id ?? $payment->id,
                'local_id' => $payment->id,
                'external_payment_id' => $payment->external_payment_id,
                'batch_id' => $payment->batch_id,
                'batch_name' => $payment->batch_name,
                'agent_id' => $payment->agent_id,
                'agent_name' => $agentName,
                'agent_pic' => $agentPic,
                'agent_phone' => $agentPhone,
                'status' => $payment->status_pembayaran,
                'status_pembayaran' => $payment->status_pembayaran,
                'payment_method' => $payment->metode_pembayaran,
                'total_amount' => $payment->total_pembayaran,
                'total_harga' => $payment->total_pembayaran,
                'detail' => $detailPesanan,
                'detail_pesanan' => $detailPesanan,
                'created_at' => $payment->created_at ? $payment->created_at->toISOString() : null,
                'updated_at' => $payment->updated_at ? $payment->updated_at->toISOString() : null,
                'qris_rrn' => $payment->qris_rrn,
                'qris_string' => $payment->qris_string,
                'qris_nmid' => $payment->qris_nmid,
                'qr_code' => $payment->qris_string, // Alias for compatibility
            ],
        ];
    }

    /**
     * Check payment status from external API (tokodigi)
     * Maps external status to local status
     * 
     * @param string|int $externalPaymentId
     * @return string|null - Mapped status or null if failed
     */
    protected function checkExternalPaymentStatus(string|int $externalPaymentId): ?string
    {
        try {
            // API endpoint: GET /api/umroh/payment?id={payment_id}
            // Response: Array langsung [{...}], bukan {data: {...}}
            $response = Http::timeout(10)->get("{$this->tokodigiApiUrl}/api/umroh/payment", [
                'id' => $externalPaymentId,
            ]);

            if (!$response->successful()) {
                return null;
            }

            $payload = $response->json();
            
            // Response adalah array, ambil item pertama
            if (!is_array($payload) || empty($payload)) {
                return null;
            }

            $data = $payload[0] ?? [];
            
            // Cek berbagai field status yang mungkin
            $externalStatus = $data['status'] ?? $data['payment_status'] ?? $data['status_pembayaran'] ?? null;

            if (!$externalStatus) {
                return null;
            }

            return $this->mapExternalStatus($externalStatus);
        } catch (\Exception $e) {
            Log::warning('Failed to check external payment status', [
                'payment_id' => $externalPaymentId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Map external status to local status
     */
    protected function mapExternalStatus(?string $externalStatus): ?string
    {
        if (!$externalStatus) {
            return null;
        }

        $statusMap = [
            'INJECT' => Pembayaran::STATUS_SUCCESS,
            'SUCCESS' => Pembayaran::STATUS_SUCCESS,
            'SUKSES' => Pembayaran::STATUS_SUCCESS, // Added SUKSES
            'BERHASIL' => Pembayaran::STATUS_SUCCESS,
            'AKTIF' => Pembayaran::STATUS_SUCCESS,
            'VERIFY' => Pembayaran::STATUS_VERIFY,
            'WAITING' => Pembayaran::STATUS_WAITING,
            'PENDING' => Pembayaran::STATUS_WAITING,
            'FAILED' => Pembayaran::STATUS_FAILED,
            'EXPIRED' => Pembayaran::STATUS_EXPIRED,
            'CANCEL' => Pembayaran::STATUS_FAILED,
        ];

        $normalized = strtoupper(trim($externalStatus));

        return $statusMap[$normalized] ?? $normalized;
    }

    /**
     * Update local payment status without marking as success (no paid_at update)
     */
    protected function updateLocalPaymentStatus(string|int $paymentId, string $newStatus): bool
    {
        try {
            $pembayaran = Pembayaran::where('external_payment_id', (string) $paymentId)->first();
            if (!$pembayaran) {
                $pembayaran = Pembayaran::find($paymentId);
            }

            if (!$pembayaran) {
                Log::warning('Payment not found for status update', [
                    'payment_id' => $paymentId,
                    'new_status' => $newStatus,
                ]);
                return false;
            }

            $currentStatus = strtoupper((string) $pembayaran->status_pembayaran);

            // Jangan downgrade jika sudah SUCCESS
            if ($currentStatus === Pembayaran::STATUS_SUCCESS) {
                return true;
            }

            if ($currentStatus !== $newStatus) {
                $pembayaran->status_pembayaran = $newStatus;
                $pembayaran->save();

                Log::info('Payment status updated from verify', [
                    'payment_id' => $paymentId,
                    'old_status' => $currentStatus,
                    'new_status' => $newStatus,
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::warning('Failed to update local payment status', [
                'payment_id' => $paymentId,
                'new_status' => $newStatus,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
