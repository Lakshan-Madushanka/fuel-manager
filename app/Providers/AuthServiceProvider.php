<?php

namespace App\Providers;

use App\Enums\User\Role;
use App\Models\User;
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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->loadGates();
    }

    public function loadGates()
    {
        Gate::define('administrative', function (User $user) {
            return $user->role->value === Role::SUPER_ADMIN->value
                || $user->role->value === Role::OWNER->value;
        });

        Gate::define('owner', function (User $user) {
            return $user->role->value === Role::OWNER->value;
        });

        Gate::define('admin', function (User $user) {
            return $user->role->value === Role::ADMIN->value;
        });

        Gate::define('superAdmin', function (User $user) {
            return $user->role->value === Role::SUPER_ADMIN->value;
        });
    }
}
