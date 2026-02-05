<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\VerifyWaitingPaymentsJob;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Jalankan verifikasi pembayaran setiap menit
        // Polling otomatis untuk mencocokkan mutasi QRIS
        // $schedule->job(new VerifyWaitingPaymentsJob)->everyMinute();
        
        // Sync status pembayaran dari Tokodigi API setiap 15 menit
        // Cek status INJECT/SUCCESS untuk pembayaran yang berhasil
        $schedule->command('payment:sync-status')->everyFifteenMinutes();
        
        // Reset saldo_bulan setiap tanggal 1 jam 00:01
        $schedule->command('agent:reset-monthly-saldo')
            ->monthlyOn(1, '00:01')
            ->timezone('Asia/Jakarta');
        
        // Reset saldo_tahun setiap 1 Januari jam 00:02
        $schedule->command('agent:reset-yearly-saldo')
            ->yearlyOn(1, 1, '00:02')
            ->timezone('Asia/Jakarta');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
