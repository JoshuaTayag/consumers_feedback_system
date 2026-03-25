<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\View\Composers\PendingComposer;
use App\Mail\MicrosoftGraphTransport;
use Illuminate\Support\Facades\Mail;

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
        Paginator::useBootstrapFive();
        
        // Share pending notification count with app layout
        View::composer('layouts.app', PendingComposer::class);

        Mail::extend('microsoft_graph', function () {
            return new MicrosoftGraphTransport(
                config('services.microsoft_graph.client_id'),
                config('services.microsoft_graph.client_secret'),
                config('services.microsoft_graph.tenant_id'),
                config('services.microsoft_graph.user_email'),
            );
        });
    }
}
