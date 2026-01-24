<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Withdraw;
use App\Models\Pembayaran;
use App\Observers\WithdrawObserver;
use App\Observers\PembayaranObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Withdraw::observe(WithdrawObserver::class);
        Pembayaran::observe(PembayaranObserver::class);
    }
}
