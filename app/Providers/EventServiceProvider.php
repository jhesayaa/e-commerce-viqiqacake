<?php

namespace App\Providers;

use App\Models\Pesanan;
use App\Observers\PesananObserver;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Pesanan::observe(PesananObserver::class);
        Log::info('PesananObserver registered');
    }
}
