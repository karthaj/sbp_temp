<?php

namespace Shopbox\Providers;

use Gate;
use Shopbox\Models\Zpanel\Permission;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Permission::get()->map(function ($permission) {
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasPermissionTo($permission);
            });
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
