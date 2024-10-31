<?php

namespace Shopbox\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Shopbox\Models\Zpanel\ConfirmationToken;
use Shopbox\Models\Zpanel\VerificationToken;
use Shopbox\Models\Zpanel\PluginCategory;
use Shopbox\Models\Zpanel\Billing;
use Shopbox\Models\Zpanel\Plan;
use Modules\Product\ShippingZone;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Shopbox\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::model('confirmation_token', ConfirmationToken::class);

        Route::model('verification_token', VerificationToken::class);

        Route::model('plugin_category', PluginCategory::class);

        Route::bind('user', function ($value) {
            return session('store')->users()->where('user_id', $value)->where(function ($query) {
                $query->join('users', 'users.id', '=', 'store_users.user_id')->where('users.master', 0);
            })->firstOrFail();
        });

        Route::bind('billing', function ($value) {
            if(!session('store')->billings()->where('reference', $value)->count()) {
                abort('404');
            }
            if(request()->is('merchant/admin/bills/'.$value)) {
                return Billing::where('state', '<>', 2)->where('reference', $value)->first();
            }
            return Billing::where('reference', $value)->first();
        });

        Route::bind('cart', function ($value) {
            return session('store')->carts()->where('reference', $value)->first(); 
        });

        Route::bind('plan', function ($value) {
            return Plan::where('type', session('store')->plan->type)->where('slug', $value)->first(); 
        });

        parent::boot();

    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapTenantRoutes();

        $this->mapStoreRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware(['web','bindings'])
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Map tenant routes.
     *
     * @return [type] [description]
     */
    protected function mapTenantRoutes()
    {
        Route::middleware(['web', 'auth:admin', 'tenant', 'bindings'])
             ->namespace($this->namespace)
             ->group(base_path('routes/tenant.php'));
    }

    /**
     * Map store routes.
     *
     * @return [type] [description]
     */
    protected function mapStoreRoutes()
    {
        Route::middleware(['web', 'bindings', 'shop'])
             ->namespace($this->namespace)
             ->group(base_path('routes/store.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
