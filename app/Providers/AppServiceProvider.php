<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StockLedger;
use App\Observers\StockLedgerObserver;

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
        StockLedger::observe(StockLedgerObserver::class);
    }
}
