<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // 本番環境でHTTPS URLを強制
        if (app()->environment('production')) {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }

        // Railwayでの確実なHTTPS設定
        if (! app()->environment('local')) {
            URL::forceScheme('https');
        }
    }
}
