<?php

namespace Modules\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Route;
use Modules\Product\Observers\ShippingZoneMethodObserver;
use Modules\Product\Entities\ShippingZoneMethod;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\Transfer;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\ShippingZone;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();

        Route::bind('category', function ($value) {
            return Category::where('slug', $value)->where(function ($query) use ($value){
               
                $query->where('store_id', session('store')->id);
                
            })->firstOrFail();
        });

        Route::bind('brand', function ($value) {
            return Brand::where('slug', $value)->where(function ($query) use ($value){
               
                $query->where('store_id', session('store')->id);
                
            })->firstOrFail();
        });

        Route::bind('product', function ($value) {
            return Product::where('slug', $value)->where(function ($query) use ($value){
               
                $query->where('store_id', session('store')->id);
                
            })->firstOrFail();
        });

        Route::bind('shipping_zone', function ($value) {
            return ShippingZone::where('alias', $value)->where(function ($query) use ($value){
               
                $query->where('store_id', session('store')->id);
                
            })->firstOrFail();
        });

        // Route::bind('cart', function ($value) {
        //     return Cart::where('id', $value)->where(function ($query) use ($value){
               
        //         $query->where('store_id', session('store')->id);
                
        //     })->firstOrFail();
        // });

        Route::bind('transfer', function ($value) {
            return Transfer::where('reference', $value)->where(function ($query) use ($value){
               
                $query->where('store_id', session('store')->id);
                
            })->firstOrFail();
        });

        Route::bind('attribute', function ($value) {
            return Attribute::where('id', $value)->where(function ($query) use ($value){
               
                $query->where('store_id', session('store')->id);
                
            })->firstOrFail();
        });

        ShippingZoneMethod::observe(ShippingZoneMethodObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('product.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'product'
        );
        $this->publishes([
            __DIR__.'/../Config/module_product_file.php' => config_path('module_product_file.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/module_product_file.php', 'module_product_file'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/product');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/product';
        }, \Config::get('view.paths')), [$sourcePath]), 'product');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/product');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'product');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'product');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/Database/factories');
        }
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
