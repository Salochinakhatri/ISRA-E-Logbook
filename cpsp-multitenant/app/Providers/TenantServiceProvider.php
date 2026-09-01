<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\TenantManager;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind TenantManager as a singleton so the same instance
        // is shared across the entire request lifecycle.
        $this->app->singleton(TenantManager::class);
    }

    public function boot(): void
    {
        //
    }
}
