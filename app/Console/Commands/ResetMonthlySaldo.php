<?php

namespace App\Console\Commands;

use App\Models\Agent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetMonthlySaldo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:reset-monthly-saldo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset saldo_bulan semua agent menjadi 0 (dijalankan otomatis setiap awal bulan)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== RESET SALDO BULAN ===');
        
        $count = Agent::query()->update(['saldo_bulan' => 0]);
        
        Log::info('Saldo bulan berhasil direset', [
            'total_agents' => $count,
            'tanggal' => now()->toDateTimeString()
        ]);
        
        $this->info("✅ Saldo bulan {$count} agent berhasil direset ke 0");
        
        return Command::SUCCESS;
    }
}
