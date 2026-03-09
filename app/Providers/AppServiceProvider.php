<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
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
        // No pongas nada aquí relacionado con Gates o Policies
        // (o solo deja vacío si no usas otras configuraciones globales)
        if (
            app()->environment('production') || // cuando estés en producción real
            str_starts_with(config('app.url'), 'https://') || // ngrok o APP_URL https
            Request::server('HTTPS') == 'on' // servidores con HTTPS
        ) {
            URL::forceScheme('https');
        }
       
    }
}