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
    $this->registerPolicies(); // 🔥 SIEMPRE PRIMERO

    Gate::define('solo-root', function ($user) {
        return trim(strtolower($user->role ?? '')) === 'root';
    });

    Gate::define('manage-users', function ($user) {
        return trim(strtolower($user->role ?? '')) === 'root';
    });
}
}