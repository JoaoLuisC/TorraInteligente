<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

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
        // Forçar HTTPS em produção
        if (App::environment('production')) {
            URL::forceScheme('https');

            // Configurar cabeçalhos de proxy para HTTPS
            request()->server->set('HTTPS', 'on');
            request()->server->set('SERVER_PORT', '443');
            request()->server->set('HTTP_X_FORWARDED_PROTO', 'https');
            request()->server->set('HTTP_X_FORWARDED_PORT', '443');
            request()->server->set('HTTP_X_FORWARDED_SSL', 'on');

            // Forçar root URL como HTTPS
            URL::forceRootUrl(config('app.url'));
        }
    }
}
