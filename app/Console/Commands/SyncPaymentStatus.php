<?php

namespace App\Console\Commands;

use App\Models\Pembayaran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncPaymentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:sync-status {--date= : Tanggal untuk cek status (format: Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync payment status dari Tokodigi API list';

    /**
     * Base URL untuk Tokodigi API
     */
    protected string $apiUrl = 'https://tokodigi.id/api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ?? now()->format('Y-m-d');
        
        $this->info("🔄 Syncing payment status untuk tanggal: {$date}");
        Log::info('SyncPaymentStatus: Starting sync', ['date' => $date]);

        try {
            // Call API list untuk ambil semua transaksi
            // Response format: Array langsung [{...}, {...}]
            $response = Http::timeout(60)->get("{$this->apiUrl}/umroh/list", [
                'periode_date' => $date,
                'user_list' => 'alltrx',
            ]);

            if (!$response->successful()) {
                $this->error("❌ API request failed: " . $response->status());
                Log::error('SyncPaymentStatus: API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return 1;
            }

            $transactions = $response->json();
            
            // Response adalah array langsung
            if (!is_array($transactions)) {
                $this->error("❌ Unexpected response format");
                return 1;
            }

            $this->info("📦 Found " . count($transactions) . " transactions");
            Log::info('SyncPaymentStatus: Transactions found', ['count' => count($transactions)]);

            $updated = 0;
            $successCount = 0;

            foreach ($transactions as $trx) {
                $paymentId = $trx['id'] ?? null;
                $status = $trx['payment_status'] ?? null;

                if (!$paymentId || !$status) {
                    continue;
                }

                // Cari pembayaran lokal berdasarkan external_payment_id
                $pembayaran = Pembayaran::where('external_payment_id', (string) $paymentId)->first();

                if (!$pembayaran) {
                    continue;
                }

                // Update status jika berubah
                $oldStatus = $pembayaran->status_pembayaran;
                
                // Map status dari API ke status lokal
                // INJECT/SUCCESS = pembayaran berhasil
                $newStatus = $this->mapStatus($status);

                if ($oldStatus !== $newStatus) {
                    $pembayaran->status_pembayaran = $newStatus;
                    $pembayaran->save();

                    $updated++;
                    
                    if (in_array($newStatus, [Pembayaran::STATUS_SUCCESS])) {
                        $successCount++;
                        $this->info("✅ Payment {$paymentId}: {$oldStatus} → {$newStatus} (BERHASIL)");
                        
                        // TODO: Tambahkan logic komisi di sini jika payment sukses
                        // $this->processCommission($pembayaran);
                    } else {
                        $this->line("📝 Payment {$paymentId}: {$oldStatus} → {$newStatus}");
                    }

                    Log::info('SyncPaymentStatus: Status updated', [
                        'payment_id' => $paymentId,
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                    ]);
                }
            }

            $this->info("✅ Sync completed: {$updated} payments updated, {$successCount} successful");
            Log::info('SyncPaymentStatus: Completed', [
                'updated' => $updated,
                'successful' => $successCount,
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            Log::error('SyncPaymentStatus: Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }

    /**
     * Map status dari API ke status lokal
     */
    protected function mapStatus(string $apiStatus): string
    {
        $statusMap = [
            'INJECT' => Pembayaran::STATUS_SUCCESS,
            'SUCCESS' => Pembayaran::STATUS_SUCCESS,
            'BERHASIL' => Pembayaran::STATUS_SUCCESS,
            'AKTIF' => Pembayaran::STATUS_SUCCESS,
            'VERIFY' => Pembayaran::STATUS_VERIFY,
            'WAITING' => Pembayaran::STATUS_WAITING,
            'PENDING' => Pembayaran::STATUS_WAITING,
            'FAILED' => Pembayaran::STATUS_FAILED,
            'EXPIRED' => Pembayaran::STATUS_EXPIRED,
            'CANCEL' => Pembayaran::STATUS_FAILED,
        ];

        return $statusMap[strtoupper($apiStatus)] ?? $apiStatus;
    }

    /**
     * Process commission for successful payment
     * TODO: Implement commission logic
     */
    protected function processCommission(Pembayaran $pembayaran): void
    {
        // Implement commission logic here
        // - Calculate commission based on agent/affiliate/freelance
        // - Add to saldo/balance
        // - Create commission record
        Log::info('SyncPaymentStatus: Commission processing', [
            'payment_id' => $pembayaran->external_payment_id,
            'agent_id' => $pembayaran->agent_id,
        ]);
    }
}
