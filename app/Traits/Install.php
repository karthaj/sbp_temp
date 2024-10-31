<?php

namespace Shopbox\Traits;

use Chumper\Zipper\Zipper;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Front\Theme;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Front\StoreTheme;
use Shopbox\Models\Zpanel\StorePlugin;
use Shopbox\Models\Zpanel\StorePayment;
use Modules\ShopboxPay\Entities\Config;
use Shopbox\Transformers\Marketplace\ThemeTransformer;

trait Install
{
	public function installPlugin(Store $store, Plugin $plugin)
	{
		if($plugin->category->alias === 'payment') {

            if($store->payments()->where('plugin_id', $plugin->id)->count()) {
                return;
            }

    		$store_payment = new StorePayment;
    		$store_payment->store()->associate($store);
    		$store_payment->plugin()->associate($plugin);
    		$store_payment->display_name = $plugin->plugin_name;
            $store_payment->alias = $plugin->alias;
    		$store_payment->save();

    		if($plugin->alias === 'shopboxpay') {
	            $store_payment->timestamps = false;
	            $store_payment->shopbox_ipg = 1;
	            $store_payment->save();
	            $this->saveShopboxPayments($store_payment);
	        }

    	} else {

            if($store->plugins()->where('plugin_id', $plugin->id)->count()) {
                return;
            }

    		$store_plugin = new StorePlugin;
    		$store_plugin->plugin()->associate($plugin);
    		$store_plugin->store()->associate($store);
    		$store_plugin->save();

    	}

    	if($plugin->permissions->count()) {
    		$store->permissions()->saveMany($plugin->permissions);
    	}
 
	}

	protected function saveShopboxPayments(StorePayment $shopboxpay)
    {
        foreach ($shopboxpay->plugin->payments as $payment) {
  
            $config = new Config;
            $config->payment()->associate($shopboxpay);
            $config->plugin()->associate($shopboxpay->plugin);
            $config->display_name = $payment->plugin_name;
            $config->alias = str_slug($payment->plugin_name);
            $config->tdr_rate = $shopboxpay->store->plan->tdr_rate;
            $config->save();

        }
    }

	public function installTheme(Store $store, Theme $theme)
	{	
		$version  = fractal()
                    ->item($theme)
                    ->transformWith(new ThemeTransformer)
                    ->toArray()['data']['version'];

		$store_theme = new StoreTheme;
		$store_theme->store()->associate($store);
		$store_theme->theme()->associate($theme);
		$store_theme->version = $version;
		$store_theme->created_at_tz = $store_theme->freshTimestamp()->timezone($store->setting->timezone->timezone);
		$store_theme->updated_at_tz = $store_theme->freshTimestamp()->timezone($store->setting->timezone->timezone);
		$store_theme->save();

		$this->copyTheme($store, $theme->slug);
		
	}	

	protected function copyTheme(Store $store, $theme)
	{
		$store_path = resource_path('views/stores').'/'.$store->domain.'/'.$theme;

		$zipper = new Zipper;

        $zipper->zip($store_path.'.zip')->add(storage_path('app/appconfig/themes/'.$theme.'/'));

        $zipper->make($store_path.'.zip')->extractTo($store_path);

        $zipper->close();

        copyDirectory($store_path.'/assets', public_path('stores').'/'.$store->domain.'/themes/'.$theme.'/assets');
        copyDirectory(storage_path('app/appconfig/themes/'.$theme.'/meta'), public_path('stores').'/'.$store->domain.'/themes/'.$theme.'/meta');
        
        unlink($store_path.'.zip');
	}

}