<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', fn(User $user) => $user->role === 'admin');

        Gate::define('isStaff', fn(User $user) => $user->role === 'staff');

        Gate::define('isDeveloper', fn(User $user) => $user->role === 'developer');

        Gate::define('manageAppearance', fn(User $user) => $user->role === 'developer');

        Gate::define('isAdminOrStaff', fn(User $user) =>
            in_array($user->role, ['admin', 'staff'], true)
        );

        Gate::define('isStudent', fn(User $user) =>
            in_array($user->role, ['student', 'faculty'], true)
        );
    }
}
