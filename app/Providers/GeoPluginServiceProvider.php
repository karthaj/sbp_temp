<?php

namespace Shopbox\Providers;

use Illuminate\Support\ServiceProvider;
use Shopbox\Services\Geoplugin\GeoPluginWrapper;
use Shopbox\Services\Geoplugin\Contracts\GeoPluginContract;
use Shopbox\Services\Geoplugin\geoPlugin;

class GeoPluginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GeoPluginContract::class, function ($app) {
            $geoplugin = new geoPlugin();
            return new GeoPluginWrapper($geoplugin);
        });
    }
}
