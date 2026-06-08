<?php

namespace App\Providers;

use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Super admins bypass all permission/gate checks unconditionally.
        Gate::before(function ($user, string $ability) {
            if ($user instanceof SuperAdmin) {
                return true;
            }
        });
    }
}
