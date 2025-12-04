<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
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
        // The custom resolver is already configured in config/audit.php
        // No additional setup needed since it's a static method
    }
}