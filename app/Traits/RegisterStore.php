<?php

namespace Shopbox\Traits;

use Chumper\Zipper\Zipper;
use Leafo\ScssPhp\Compiler;
use Shopbox\Models\Front\Theme;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\Item;
use Modules\Page\Entities\Page;
use Shopbox\Models\Zpanel\User;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\Client;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\TimeZone;
use Shopbox\Models\Zpanel\Currency;
use Shopbox\Models\Zpanel\Referral;
use Shopbox\Models\Zpanel\Affiliate;
use Modules\Product\Entities\Option;
use Shopbox\Models\Front\StoreTheme;
use Modules\Product\Entities\TaxClass;
use Modules\Product\Entities\Category;
use Shopbox\Models\Zpanel\StorePayment;
use Shopbox\Models\Zpanel\StoreSetting;
use Modules\ShopboxPay\Entities\Config;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\OptionSet;
use Modules\EmailTemplate\Entities\Email;
use Modules\Product\Entities\StoreLocation;
use Shopbox\Transformers\Marketplace\ThemeTransformer;

trait RegisterStore
{
	
	protected function saveStoreSetting(Store $store)
	{
		$store_setting = new StoreSetting;
        $store_setting->store()->associate($store);
        $store_setting->weight()->associate(1);
        $store_setting->timezone()->associate($this->getTimezone($this->geoip->timezone));
        $store_setting->currency()->associate($this->getCurrency($this->geoip->currency));
        $store_setting->order_id_prefix = '#';
        $store_setting->meta_title = $store->store_name;
        $store_setting->enable_password = 1;
        $store_setting->password = str_random(10);
        $store_setting->password_hash = bcrypt($store_setting->password);
        $store_setting->message = "We'll be back soon!";
        $store_setting->store_pickup_display_name = "Store Pickup";
        $store_setting->save();
	}

	protected function getTimezone($timezone)
    {
    	
    	if(Timezone::where('timezone', $timezone)->count()) {
    		return Timezone::where('timezone', $timezone)->first();
    	}

    	return Timezone::where('timezone', 'Asia/Colombo')->first();

    }

    protected function getCurrency($currency)
    {
    	
    	if(Currency::where('iso_code', $currency)->count()) {
    		return Currency::where('iso_code', $currency)->first();
    	}
        
        return Currency::where('iso_code', 'LKR')->first();

    }

	protected function storeClient(Store $store)
	{
		$client = new Client;
        $client->store()->associate($store);
        $client->secret = str_random(40);
        $client->redirect = getStoreUrl($store).'/shopboxpay/response';
        $client->merchant_redirect = getStoreUrl($store).'/sbpay/merchant/response';
        $client->save();
	}

	protected function saveDefaultCategory(Store $store)
    {
        $category = new Category;
        $category->store()->associate($store);
        $category->name = '-- no parent category --';
        $category->slug = 'no-parent-category';
        $category->is_root_category = 1;
        $category->save();
    }

    protected function saveDefaultTaxClass(Store $store)
    {
        $class = new TaxClass;
        $class->store()->associate($store);
        $class->name = 'Default Tax Class';
        $class->save();
    }

    protected function applyDefaultTheme(Store $store)
    {
        $theme = Theme::where('default', 1)->first();

        if(!$theme)
        	return;

        $version  = fractal()
                    ->item($theme)
                    ->transformWith(new ThemeTransformer)
                    ->toArray()['data']['version'];

		$store_theme = new StoreTheme;
		$store_theme->store()->associate($store);
		$store_theme->theme()->associate($theme);
		$store_theme->version = $version;
		$store_theme->active = 1;
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

       	$this->compileAssets($store, $theme);
	}

	protected function compileAssets(Store $store, $theme) 
    {	
    	if(!file_exists(storage_path('app/appconfig/themes/'.$theme.'/config/setting.json'))) {
    		return;
    	}

    	$config = json_decode(file_get_contents(storage_path('app/appconfig/themes/'.$theme.'/config/setting.json')), true);

        $scss = new Compiler();
        $scss->setImportPaths(storage_path('app/appconfig/themes/'.$theme.'/assets/'));
        $scss->setFormatter('Leafo\ScssPhp\Formatter\Compressed');
        $scss->setVariables(array_except($config['current'], ['sections', 'content_for_index']));

        $scss->registerFunction("typography", function($data) {
          
            $data = array_flatten($data);

            $data = array_where($data, function ($value, $key) {
                        
                        if($value != 'string' && $value != 'keyword' && $value != '') {
                            return $value;
                        }

                    });
            
            $data = implode('+', $data);
            
            if(str_contains($data, 'Google')) {
               
                $type_parts = explode('_', $data);
                $type_name = $type_parts[1];
                $font_family = str_replace("+"," ",$type_name);

                if(last($type_parts) === 'serif') {
                    $font_family .= ', serif';
                } else {
                    $font_family .= ', "HelveticaNeue", "Helvetica Neue", sans-serif';
                }
       
                $font_weight = $type_parts[2];

                return $font_family.'|'.$font_weight;
        
            }

        });

        $css = $scss->compile('@import "customized.scss";');

        file_put_contents(public_path('stores/'.$store->domain.'/themes/'.$theme.'/assets/customized.css'),$css);
    }

    protected function saveStorePayments(Store $store)
    {
        $plugins = $this->getPaymentPlugins();

        if($plugins->count()) {
            foreach($plugins as $plugin) {
                foreach($plugin->permissions as $permission) {
                    if($store->plan->permissions->contains('id', $permission->id)) {
                        $payment = new StorePayment;
                        $payment->store()->associate($store);
                        $payment->plugin()->associate($plugin);
                        $payment->display_name = $plugin->plugin_name;
                        $payment->alias = $plugin->alias;
                        $payment->save();

                        if($plugin->alias === 'shopboxpay') {
                            $payment->timestamps = false;
                            $payment->shopbox_ipg = 1;
                            $payment->save();
                            $this->saveShopboxPayments($payment);
                        }
                        
                        break;
                    }
                }
            }
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

    protected function getPaymentPlugins()
    {
        $payments = collect();
        $plugins = Plugin::whereNotNull('type')->get();

        foreach($plugins as $plugin)
        {
            if($plugin->category->alias === 'payment') {
                $payments->push($plugin);
            }
        }

        return $payments;
    }

    protected function saveDefaultMenu(Store $store)
    {
        $menu = new Menu;
        $menu->store()->associate($store);
        $menu->name = 'main menu';
        $menu->slug = 'main-menu';
        $menu->active = 1;
        $menu->save();

        $links = collect([['name' => 'home', 
                            'order' => 1, 
                            'url' => getStoreUrl($store), 
                            'custom' => 1
                           ],
                           ['name' => 'categories', 
                            'order' => 3, 
                            'url' => 'categories', 
                            'custom' => 0
                           ],
                        ]);

        foreach ($links as $link) {
            $item = new Item;
            $item->menu()->associate($menu);
            $item->name = $link['name'];
            $item->order = $link['order'];
            $item->url = $link['url'];
            $item->custom = $link['custom'];
            $item->save();
        }
    }

    protected function saveDefaultPage(Store $store)
    {
        $page = new Page;
        $page->store()->associate($store);
        $page->type = 'contact';
        $page->title = 'contact';
        $page->slug = 'contact';
        $page->enable_form = 1;
        $page->active = 1;
        $page->save();
    }

    protected function saveEmailTemplates(Store $store)
    {
        $templates = collect([['email_template_name' => 'Order confirmation', 
                            'slug' => 'order-confirmation', 
                            'email_subject' => 'Order Confirmation', 
                            'email_body' => 'We received your order and we\'ll let you know when we ship it out.'
                           ],
                           ['email_template_name' => 'Abandoned cart notification', 
                            'slug' => 'abandoned-cart-notification', 
                            'email_subject' => 'Complete your purchase', 
                            'email_body' => 'You recently visited our online store and we noticed that you didn\'t complete your order. To complete your order right now, just click on the link below:'],
                            ['email_template_name' => 'Order status', 
                            'slug' => 'order-status', 
                            'email_subject' => 'Order status changed', 
                            'email_body' => 'An order you recently placed on our website has had its status changed.'],
                            ['email_template_name' => 'Return confirmation', 
                            'slug' => 'return-confirmation', 
                            'email_subject' => 'Return Request Confirmation', 
                            'email_body' => 'A summary of your return is shown below.'],
                            ['email_template_name' => 'Return Status', 
                            'slug' => 'return-status', 
                            'email_subject' => 'Return Status Update', 
                            'email_body' => 'You recently submitted a return request on our store for the following item(s):'],
                        ]);

        foreach($templates as $template) {
            $email = new Email;
            $email->store()->associate($store);
            $email->email_template_name = $template['email_template_name'];
            $email->slug = $template['slug'];
            $email->email_subject = $template['email_subject'];
            $email->email_body = $template['email_body'];
            $email->save();
        }
        
    }

    protected function saveDefaultStoreLocation(Store $store, User $user)
    {
        $location = new StoreLocation;
        $location->store()->associate($store);
        $location->name = 'main store';
        $location->country()->associate($store->country_id);
        $location->phone = $store->phone;
        $location->active = 1;
        $location->online_sales = 1;
        $location->save();

        $user->storeLocations()->attach($location);
    }

     protected function saveDefaultOptionSets($store)
    {   
        $variants = collect([['name' => 'Size (Example)', 
                            'public_name' => 'Size', 
                            'group_type' => 'multiple choice', 
                            'display_style' => '[S]'
                           ],
                           ['name' => 'Colour (Example)', 
                            'public_name' => 'Colour', 
                            'group_type' => 'swatch', 
                            'display_style' => '[CS]'
                           ],
                        ]);

        foreach($variants as $variant) {
            $attribute = new Attribute;
            $attribute->store()->associate($store);
            $attribute->name = $variant['name'];
            $attribute->public_name = $variant['public_name'];
            $attribute->group_type = $variant['group_type'];
            $attribute->display_style = $variant['display_style'];
            $attribute->save();

            if($attribute->group_type === 'multiple choice') {

                $this->createListOptions($attribute);

            } else if($attribute->group_type === 'swatch') {

                $this->createSwatchOptions($attribute);
            }

            $option_set = new OptionSet;
            $option_set->name = $variant['name'].' Set';
            $option_set->store()->associate($store);
            $option_set->save();

            $option_set->attributes()->attach($attribute->id);
        }
    }

    protected function createListOptions(Attribute $attribute)
    {
        $values = collect([['name' => 'Small', 
                            'type' => 'list',
                            'sort_order' => 1
                           ],
                           ['name' => 'Medium', 
                            'type' => 'list', 
                            'sort_order' => 2
                           ],
                           ['name' => 'Large', 
                            'type' => 'list', 
                            'sort_order' => 3
                           ],
                           ['name' => 'Extra Large', 
                            'type' => 'list', 
                            'sort_order' => 4
                           ],   
                        ]);

        foreach($values as $value) {

            $option = new Option;
            $option->attribute()->associate($attribute);
            $option->name = $value['name'];
            $option->type = $value['type'];
            $option->sort_order = $value['sort_order'];
            $option->save();
        }
    }

    protected function createSwatchOptions(Attribute $attribute)
    {
        $values = collect([['name' => 'Black', 
                            'color' => '#000000',
                            'type' => 'color',
                            'sort_order' => 1
                           ],
                           ['name' => 'White', 
                            'color' => '#ffffff',
                            'type' => 'color', 
                            'sort_order' => 2
                           ],
                           ['name' => 'Blue', 
                            'color' => '#0006ff',
                            'type' => 'color', 
                            'sort_order' => 3
                           ],
                           ['name' => 'Red', 
                            'color' => '#ff0000',
                            'type' => 'color', 
                            'sort_order' => 4
                           ], 
                           ['name' => 'Green', 
                            'color' => '#0eff00',
                            'type' => 'color', 
                            'sort_order' => 5
                           ],  
                           ['name' => 'Yellow',
                            'color' => '#fff900', 
                            'type' => 'color', 
                            'sort_order' => 6
                           ],
                        ]);

        foreach($values as $value) {

            $option = new Option;
            $option->attribute()->associate($attribute);
            $option->name = $value['name'];
            $option->type = $value['type'];
            $option->color = $value['color'];
            $option->sort_order = $value['sort_order'];
            $option->save();
        }
    }

    protected function copyDefaultFolders(Store $store)
    {
    	$folder_path = public_path('stores').'/'.$store->domain;
        $zipper = new Zipper;
        $zipper->make(storage_path('app/appconfig/default/str_default_folders.zip'))->extractTo($folder_path);
    }

    protected function saveReferral(Store $store, $ref)
    {
        $affiliate = Affiliate::where('ref_code', $ref)->first();

        if($affiliate) {
            $referral = new Referral;
            $referral->affiliate()->associate($affiliate);
            $referral->store()->associate($store);
            $referral->save();
        }
    }

}