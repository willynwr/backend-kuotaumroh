<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Withdraw;
use App\Observers\WithdrawObserver;

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
    }
}
