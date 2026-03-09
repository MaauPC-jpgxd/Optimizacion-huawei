<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Si más adelante creas Policies, las pones aquí
        // Ejemplo: 'App\Models\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Aquí defines tus Gates (permisos personalizados)
        Gate::define('manage-users', function ($user) {
        $rol = trim(strtolower($user->role ?? ''));  // limpia espacios y convierte a minúsculas
        return $rol === 'root';
    });
        // Puedes agregar más Gates en el futuro aquí
        // Gate::define('editar-inventario', fn ($user) => $user->role === 'root' || $user->role === 'admin');
    }
}