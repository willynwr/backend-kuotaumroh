<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Withdraw;
use App\Models\Agent;
use Illuminate\Support\Facades\DB;

class FixApprovedWithdrawals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'withdraw:fix-approved';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix approved withdrawals yang belum mengurangi saldo agent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mencari withdrawal dengan status approve...');
        
        $approvedWithdrawals = Withdraw::where('status', 'approve')
            ->with('agent')
            ->get();
        
        if ($approvedWithdrawals->isEmpty()) {
            $this->info('Tidak ada withdrawal dengan status approve.');
            return 0;
        }
        
        $this->info('Ditemukan ' . $approvedWithdrawals->count() . ' withdrawal yang sudah approve.');
        
        $this->table(
            ['ID', 'Agent', 'Jumlah', 'Tanggal Approve', 'Saldo Agent Saat Ini'],
            $approvedWithdrawals->map(function ($w) {
                return [
                    $w->id,
                    $w->agent->nama_pic ?? 'N/A',
                    'Rp ' . number_format($w->jumlah, 0, ',', '.'),
                    $w->date_approve ?? 'N/A',
                    'Rp ' . number_format($w->agent->saldo ?? 0, 0, ',', '.')
                ];
            })
        );
        
        if (!$this->confirm('Apakah Anda ingin mengurangi saldo agent untuk semua withdrawal di atas?')) {
            $this->info('Operasi dibatalkan.');
            return 0;
        }
        
        $processed = 0;
        $failed = 0;
        
        foreach ($approvedWithdrawals as $withdrawal) {
            try {
                DB::beginTransaction();
                
                $agent = $withdrawal->agent;
                $oldSaldo = $agent->saldo;
                
                // Kurangi saldo
                $agent->decrement('saldo', $withdrawal->jumlah);
                
                // Set date_approve jika belum ada
                if (!$withdrawal->date_approve) {
                    $withdrawal->update([
                        'date_approve' => now()->format('Y-m-d')
                    ]);
                }
                
                $newSaldo = $agent->fresh()->saldo;
                
                DB::commit();
                
                $this->line("✓ Withdrawal ID {$withdrawal->id}: Saldo {$agent->nama_pic} dikurangi dari Rp " . number_format($oldSaldo, 0, ',', '.') . " menjadi Rp " . number_format($newSaldo, 0, ',', '.'));
                $processed++;
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("✗ Gagal memproses Withdrawal ID {$withdrawal->id}: " . $e->getMessage());
                $failed++;
            }
        }
        
        $this->info("\n=== SELESAI ===");
        $this->info("Berhasil diproses: {$processed}");
        if ($failed > 0) {
            $this->error("Gagal diproses: {$failed}");
        }
        
        return 0;
    }
}

