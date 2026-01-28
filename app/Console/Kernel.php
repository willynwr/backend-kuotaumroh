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
        $schedule->job(new VerifyWaitingPaymentsJob)->everyMinute();
        
        // Sync status pembayaran dari Tokodigi API setiap 15 menit
        // Cek status INJECT/SUCCESS untuk pembayaran yang berhasil
        $schedule->command('payment:sync-status')->everyFifteenMinutes();
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
