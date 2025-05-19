<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('can-do', function (User $user, string $permission) {
            return $user->hasPermission($permission);
        });

        Gate::define('can-any', function (User $user, array $permissions) {
            return $user->hasAnyPermission($permissions);
        });

        Gate::define('can-all', function (User $user, array $permissions) {
            return $user->hasAllPermissions($permissions);
        });
    }
}


