<?php

namespace Shopbox\Console;

use DB;
use Carbon\Carbon;
use Shopbox\Traits\Stock;
use Chumper\Zipper\Zipper;
use Shopbox\Models\Zpanel\Store;
use GuzzleHttp\Client as Guzzle;
use Shopbox\Models\Zpanel\Billing;
use Modules\Product\Entities\Cart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Shopbox\Models\Front\StoreTheme;
use Illuminate\Support\Facades\File;
use Shopbox\Models\Zpanel\BillingItem;
use Modules\Customer\Entities\Customer;
use Shopbox\Events\Account\BillGenerated;
use Shopbox\Events\Account\BillNotSettled;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Notification;
use Shopbox\Notifications\Merchant\PlanExpired;
use Shopbox\Notifications\Merchant\TrialExpired;
use Shopbox\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Product\Entities\StoreStock;
use Modules\Order\Entities\Order;
use Shopbox\Mail\Order\OrderConfirmation;

class Kernel extends ConsoleKernel
{
	use Stock;
	
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        
        $schedule->call(function () {
            // Remove draft products.
            DB::table('products')->where('state', 0)->delete();

            // Remove draft bills and services.
            Billing::where('state', 2)->delete();
            DB::table('services')->where('state', 2)->delete();
			
			// Deactive expired stores
            $this->deactivateExpiredStores();

            // Deactive services if store expired.
            $this->deactivateStoreServices();

            // Send bill reminders.
            $this->sendBillReminders();

            // Expire discounts
            $this->deactiveExpiredDiscounts();

			// Restock cart
            $this->restockCart();
			
			// Delete trial stores
            $this->removeTrialStores();
			
        })->dailyAt('00:05');

        $schedule->call(function () {
            // Biil generate
            $stores = Store::where('suspended', 0)->where('active', 1)->where('plan_id', '<>', 1)->where('plan_id', '<>', 5)->whereDate('expiry_date', Carbon::now()->subDay()->toDateString())->get();
            if($stores->count()) {
                foreach ($stores as $store) {
                    $this->generateBill($store);
                }
            }

        })->dailyAt('00:15');
		

        // Update store themes
		
        $schedule->call(function () {
		/*
            $templates = StoreTheme::with('theme')->get();

            foreach($templates as $template) {
                $store = $template->store->domain;
                $theme = $template->theme->slug;
                $theme_path = resource_path('views/stores').'/'.$store.'/'.$theme;
                $theme_config = json_decode(file_get_contents(storage_path('app/appconfig/themes/'.$theme.'/config/setting.json')), true);

                if($theme_config['version'] !== $template->version) {
					
					copy(resource_path('views/stores/'.$store.'/'.$theme.'/config/setting.json'), resource_path('views/stores/'.$store.'/'.$theme.'/config/settings_backup.json'));
                    
                    $user_settings = json_decode(file_get_contents(resource_path('views/stores/'.$store.'/'.$theme.'/config/setting.json')), true);
                    $user_settings['version'] = $theme_config['version'];
                    $user_settings['meta'] = $theme_config['meta'];

                    $zipper = new Zipper;
                    $zipper->zip($theme_path.'/'.$theme.'.zip')->add(storage_path('app/appconfig/themes/'.$theme.'/'));
                    $zipper->make($theme_path.'/'.$theme.'.zip')->extractTo($theme_path);
                    $zipper->close();

                    copyDirectory($theme_path.'/assets', public_path('stores').'/'.$store.'/themes/'.$theme.'/assets');
                    copyDirectory(storage_path('app/appconfig/themes/'.$theme.'/meta'), public_path('stores').'/'.$store.'/themes/'.$theme.'/meta');
					
                    $settings = array_replace_recursive($theme_config, $user_settings);
					$sections = array_except($settings['current']['sections'], array_keys(array_diff_key($settings['current']['sections'], $user_settings['current']['sections'])));
                    $content_for_index = array_except($settings['current']['content_for_index'], array_keys(array_diff_key($settings['current']['content_for_index'], $user_settings['current']['content_for_index'])));
                    $settings['current']['sections'] = $sections;
                    $settings['current']['content_for_index'] = $content_for_index;

                    file_put_contents($theme_path.'/config/setting.json', json_encode($settings, JSON_PRETTY_PRINT));
                    unlink($theme_path.'/'.$theme.'.zip');

                    $template->update([
                        'version' => $theme_config['version']
                    ]);

                }
            }*/
			
			/*$emails = DB::table('temp_emails')->get();
			
			foreach($emails as $email) {
				
				$customer = Customer::where('is_guest', 0)->where('email', $email->email)->first();
				$store = Store::find($email->store_id);
				if($customer && $store)  {

					$token = hash_hmac('sha256', str_random(40), 'b"»Èñ\x05\x12z\x1D-gž)UƒG Xm‘Ø\x05\x10ÊˆdÊÞ‘¼JæÂÃ"');
					DB::delete('delete from password_resets where email = ?', [$customer->email]);
					DB::insert('insert into password_resets (email, token, created_at) values (?, ?, ?)', [$customer->email, Hash::make($token), new Carbon]);
					DB::delete('delete from temp_emails where email = ?', [$customer->email]);
					Notification::send($customer, new ResetPasswordNotification($token, $store));
				}
            }
            */
            /* 
			// add stocks with 0 qty for given store
			$store = Store::find(11038);
			
			if($store && $store->storeLocations->count()) {
                foreach($store->storeLocations as $location) {
					foreach($store->stocks as $stock) {
						if(!$location->stocks()->where('stock_id', $stock->id)->count()) {
							$store_stock = new StoreStock;
							$store_stock->store()->associate($store);
							$store_stock->stock()->associate($stock);
							$store_stock->storeLocation()->associate($location);
							$store_stock->save();
						}
					}					
                }
            }
          
            /*
			// order confirmation email for given order
			$order = Order::find(94);
			Mail::to($order->store->trans_email)->queue(new OrderConfirmation($order));
            Mail::to($order->customer)->queue(new OrderConfirmation($order));*/
			

        })->cron('* * * * * *');
		
		// update exchange rates
		$schedule->call(function () {
            $client = new Guzzle;
            $response = $client->request('GET', 'https://openexchangerates.org/api/latest.json?app_id=d22a086e821a461abfaa3e6b7651c008&base=USD');
            $rates = json_decode($response->getBody(), true)['rates'];
            $rates = json_encode($rates);
            $script = "var Currency = {
                rates: $rates,
                convert: function(amount, from, to) {
                    return (amount * this.rates[to]) / this.rates[from];
                }
            };";
            file_put_contents(public_path('js/currencies.js'), $script);
        })->hourly();
		
		//publish products
		$schedule->call(function () {
          $this->publishProducts();
        })->everyMinute();
		
    }
	
	protected function removeTrialStores()
    {
        $stores = Store::where('plan_id', 1)->whereRaw('DATEDIFF(NOW(), updated_at) > 30')->get();

        if($stores->count()) {
             foreach ($stores as $store) {
                File::deleteDirectory(resource_path('views/stores/'.$store->domain));
                File::deleteDirectory(public_path('stores/'.$store->domain));
                $store->users()->where('master', 0)->forceDelete();
                $store->forceDelete();
             }
        }
    }
	
	protected function publishProducts()
    {   
        DB::table('products')->where('blocked', 0)
                                ->where('active', 0)
                                ->whereNotNull('publish_on')
                                ->whereRaw('publish_on <= NOW()')
                                ->update(['active' => 1, 'publish_on' => null]);
    }
	
	protected function restockCart()
	{
		$carts = Cart::where('stock_reserved', 1)->get();

		foreach ($carts as $cart) {
			if($cart->created_at->diffInHours(Carbon::now()) >= 24) {
				$cart->stock_reserved = 0;
				$cart->save();
				$this->restock($cart);
			}
		}
	}

    protected function deactivateExpiredStores()
    {
        $stores = Store::where('suspended', 0)->where('active', 1)
                        ->whereRaw('DATE_FORMAT(DATE_ADD(expiry_date, INTERVAL grace_period DAY), "%Y-%m-%d") = ?', [Carbon::now()->subDay()->toDateString()])->pluck('id');

        if($stores->count()) {
            Store::whereIn('id', $stores)->update([
              'active' => 0
            ]);
        }
        
    }

    protected function deactiveExpiredDiscounts()
    {
        DB::table('discounts')->whereNotNull('expires_at')
        ->whereDate('expires_at', '<=', Carbon::now()->toDateString())
        ->where('active', 1)
        ->update([
            'active' => 0
        ]);
    }

    protected function sendBillReminders()
    {
        $bills = Billing::whereDate('created_at', '<>', Carbon::now()->toDateString())->where('state', 0)->get();
        if($bills->count()) {
            foreach ($bills as $bill) {
                if($bill->reminders->count() <= 5) {
                    event(new BillNotSettled($bill));
                }
            }
        }
    }

    protected function deactivateStoreServices()
    {
        $stores = Store::where('suspended', 0)->where('active', 0)->get();
        if($stores->count()) {
            foreach ($stores as $store) {
				$this->uninstallPlugins($store);
				$store->services()->where('state', 1)->whereNull('theme_id')->update([
					'state' =>  0,
					'recurring' =>  0
				]);
				
				// notify plan expired
				if($store->plan->slug === 'trial') {
					$store->notify(new TrialExpired($store));
				} else {
					$store->notify(new PlanExpired($store));
				}
				
				$store->update([
                    'active' => 2
                ]);
            }
        }
    }

    protected function uninstallPlugins(Store $store)
    {
        $services = $store->services()->where('state', 1)->whereNotNull('plugin_id')->get();

        if($services->count()) {

            foreach($services as $service) {

                if($service->plugin->category->alias === 'payment') {
                    $store->payments()->where('plugin_id', $service->plugin_id)->delete();
                } else {
                    $store->plugins()->where('plugin_id', $service->plugin_id)->delete();
                }

                // Remove permissions from store and its users.
                if($service->plugin->permissions->count()) {
                    $store->permissions()->detach($service->plugin->permissions->pluck('id')->all());
                    foreach($store->users as $user) {
                        $user->permissions()->detach($service->plugin->permissions->pluck('id')->all());
                    }
                }
            }
        }
    }

    protected function generateBill(Store $store)
    {
        $services = $store->services()->where('recurring', 1)->where('state', 1)->get();
        $service = $store->services()->where('plan_id', $store->plan_id)->where('state', 1)->first();

        if(!$services->count() || !$service) {
            return;
        }

        $billing = new Billing;
        $billing->reference = generateBillRef();
        $billing->store()->associate($store);
        $duration = getFrequency($service->updated_at, $service->ends_at);

        if($duration == 1) {
            $billing->amount = $store->plan->monthly;
            $billing->total_payable = $store->plan->monthly;
        } else if($duration == 3) {
            $billing->amount = $store->plan->quaterly;
            $billing->total_payable = $store->plan->quaterly;
        } else if($duration == 6) {
            $billing->amount = $store->plan->half_monthly;
            $billing->total_payable = $store->plan->half_monthly;
        } else if($duration == 12) {
            $billing->amount = $store->plan->yearly;
            $billing->total_payable = $store->plan->yearly;
        }

        $billing->type = 1;
        $billing->save();

        $this->storeBillingAddress($billing);
        $this->storeBillingItems($billing, $services, $duration);

        event(new BillGenerated($billing));

    }

    protected function storeBillingItems(Billing $billing, $services, $duration)
    {
        if($services->count()) {
            foreach ($services as $service) {
                $item = new BillingItem;
                $item->billing()->associate($billing);
                $item->service()->associate($service);
                $item->amount = $billing->amount;
                $item->ends_at = Carbon::now()->addMonths($duration);
                $item->save();
            }
        }
    }

    protected function storeBillingAddress(Billing $billing)
    {
        $billing->address()->create([
            'company' => $billing->store->company ?: $billing->store->store_name,
            'address1' => $billing->store->address1,
            'address2' => $billing->store->address2,
            'country' => $billing->store->country->name,
            'state' => $billing->store->state ? $billing->store->state->iso_code : null,
            'city' => $billing->store->city,
            'postcode' => $billing->store->postcode,
            'phone' => $billing->store->phone
        ]);
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
