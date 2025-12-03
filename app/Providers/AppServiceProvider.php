<?php

namespace App\Providers;

use App\Models\Master\Karyawan;
use App\Models\User;
use App\Observers\Master\KaryawanObserver;
use App\Observers\UserObserver;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Gate;
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
        Karyawan::observe(KaryawanObserver::class);
        User::observe(UserObserver::class);
        Gate::policy(\Spatie\Permission\Models\Role::class, RolePolicy::class);
    }
}
