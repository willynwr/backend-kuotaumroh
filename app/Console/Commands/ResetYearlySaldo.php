<?php

namespace App\Console\Commands;

use App\Models\Agent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetYearlySaldo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:reset-yearly-saldo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset saldo_tahun semua agent menjadi 0 (dijalankan otomatis setiap awal tahun)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== RESET SALDO TAHUN ===');
        
        $count = Agent::query()->update(['saldo_tahun' => 0]);
        
        Log::info('Saldo tahun berhasil direset', [
            'total_agents' => $count,
            'tanggal' => now()->toDateTimeString()
        ]);
        
        $this->info("✅ Saldo tahun {$count} agent berhasil direset ke 0");
        
        return Command::SUCCESS;
    }
}
