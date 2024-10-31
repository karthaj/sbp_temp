<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Models\Zpanel\State;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\RestrictedDomain;
use Shopbox\Models\Zpanel\Discount;
use Shopbox\Models\Zpanel\Billing;
use Shopbox\Models\Zpanel\BillingInvoice;
use Modules\Product\Entities\Attribute;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderDetail;
use Modules\Order\Entities\OrderState;
use Modules\Order\Entities\OrderHistory;
use Shopbox\Transformers\StateTransformer;
use Modules\Product\Entities\ShippingZoneMethod;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Emails\OrderStatusEmail;

if(!function_exists('getActiveStorePayments')) {

    function getActiveStorePayments (Store $store)
    {
        $data = [];

        if($store->payments()->where('active', 1)->count()) {

            $payments = $store->payments()->with(['payments.plugin', 'plugin'])->where('active', 1)->get();

            foreach ($payments as $payment) {

                if($payment->payments->count()) {

                    foreach($payment->payments as $payment) {
                        if($payment->plugin->logo) {
                            array_push($data, asset('modules/'.$payment->plugin->alias.'/'.$payment->plugin->logo));
                        }
                    }

                } else if($payment->plugin->logo) {
                    array_push($data, asset('modules/'.$payment->plugin->alias.'/'.$payment->plugin->logo));
                }
            }

        }

        return array_values(array_unique($data));
    }

}

if(!function_exists('getProductImage')) {
    function getProductImage(OrderDetail $item)
    {

        if($item->product_attribute && $item->product_attribute->image) {
            return url(getStoreUrl($item->store).'/stores/'.$item->store->domain.'/product/'. $item->product_attribute->image->small_default);
        }

        if($item->product->images->where('cover', 1)->count()) {

            $image = $item->product->images->where('cover', 1)->first()->small_default;

            return url(getStoreUrl($item->store).'/stores/'.$item->store->domain.'/product/'.$image);
        }

        return url(getStoreUrl($item->store).'/assets/img/ProductDefault.gif');

    }
}

if(!function_exists('formatMoney')) {

    function formatMoney (array $rates, $store_currency, $amount, $from, $to)
    {
        $rate = 0;

        if ($from === $store_currency) {
            $rate = $rates[$to];
        } else if ($to === $store_currency) {
            $rate = 1 / $rates[$from];
        } else {
            $rate = $rates[$to] * (1 / $rates[$from]);      
        }

       return number_format($amount * $rate, 2);
    }

}

if(!function_exists('getStoreUrl')) {

    function getStoreUrl (Store $store)
    {
        $store_url = '';

        if($store->main && !$store->block_domain) {
            $store_url = config('domain.protocol').$store->store_url;
        } else {
            $store_url =  config('domain.protocol').$store->domain.'.'.config('domain.app_domain');
        }

        return $store_url;
    }

}

if(!function_exists('getStoreDomain')) {

    function getStoreDomain (Store $store)
    {
        $domain = '';

        if($store->main && !$store->block_domain) {
            $domain = $store->store_url;
        } else {
            $domain =  $store->domain.'.'.config('domain.app_domain');
        }

        return $domain;
    }

}

if(!function_exists('getFrequency')) {

    function getFrequency($start_date, $end_date)
    {
        $date1_data = explode('-', $start_date->toDateString());
        $date2_data = explode('-', $end_date->toDateString());
        $date1 = Carbon::createMidnightDate($date1_data[0], $date1_data[1], $date1_data[2]);
        $date2 = Carbon::createMidnightDate($date2_data[0], $date2_data[1], $date2_data[2]);

        return $date1->diffInMonths($date2);
    }

}

if(!function_exists('checkAccountStatus')) {

    function checkAccountStatus (Store $store)
    {
        $redirect = '';
      
        if($store->suspended) {
            $redirect = redirect()->route('expire.suspend');
        }
        else if($store->plan->slug === 'trial') {
            if($store->active != 1) {
                $redirect = redirect()->route('expire.trial.index');
            }
        } else if($store->plan->slug !== 'trial') {
            if($store->active != 1) {
                $redirect = redirect()->route('expire.plan');
            }
        }

        return $redirect;
    }

}

if(!function_exists('initializeStore')) {

    function initializeStore ()
    {
        $domain = request()->getHttpHost();
        $domain = explode('.', $domain);
        $store = '';

        if(count($domain) == 3 || count($domain) == 4) {
            if($domain[0] === 'www') {
                $store = Store::where('main', 1)->where('store_url', implode($domain, '.'))->first();
            } else {
                $current_domain = $domain[0];
                array_forget($domain, 0);
                if(implode($domain, '.') === config('domain.app_domain')) {
                    $store = Store::where('domain', $current_domain)->first();
					if(!request()->preview && !request()->theme_id && $store && $store->main && !$store->block_domain && $store->store_url) {
						return redirect('//'.$store->store_url);
					}
                } else {
                    $store = Store::where('main', 1)->where('store_url', $current_domain.'.'.implode($domain, '.'))->first();
					if($store && $store->main && $store->block_domain) {
						return redirect('//'.$store->domain.'.'.config('domain.app_domain'));
					}
                }
            }
        } else if(count($domain) == 2) {
            if(implode($domain, '.') === config('domain.app_domain')) {
                return redirect('/landing');
            }

            $store = Store::where('main', 1)->where('store_url', implode($domain, '.'))->first();
			
			if($store && $store->main && $store->block_domain) {
				return redirect('//'.$store->domain.'.'.config('domain.app_domain'));
			}
			
        }
       
        if(!$store) {
            return abort('404');
        }

        session()->put('store', $store);
        config()->set('app.url', getStoreUrl($store));

    }

}

if(!function_exists('getStoreDomain')) {

    function getStoreDomain (Store $store)
    {
        $store_domain = '';

        if($store->main) {
            $store_domain = $store->store_url;
        } else {
            $store_domain = $store->domain.'.'.config('domain.app_domain');
        }

        return $store_domain;
    }

}

if(!function_exists('formattedFileSize')) {

    function formattedFileSize ($bytes, $decimals = 2)
    {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

}

if(!function_exists('updateOrderStatus')) {

    function updateOrderStatus (Order $order, $to)
    {
        $status = OrderState::where('slug', $to)->first();

        if(!$status) {
            return;
        }

        if($status->send_email) {
            Mail::to($order->customer->customerEmail)->queue(new OrderStatusEmail($order));
        }

        $order->state()->associate($status);
        $order->save();

        $history = new OrderHistory;
        $history->order()->associate($order);
        $history->state()->associate($status);
        $history->created_at_tz = $history->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
        $history->updated_at_tz = $history->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
        $history->save();
    }

}

if (!function_exists('getRealIpAddress')) {

	function getRealIpAddress ()
	{
	      if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
	      {
	         $ip = $_SERVER['HTTP_CLIENT_IP'];
	      }
	      else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
	      {
	         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	      }
	      else
	      {
	         $ip = $_SERVER['REMOTE_ADDR'];
	      }

	      return $ip;
	}
	
}


if (!function_exists('getStates')) {

	function getStates(Country $country)
    {
       
       $states = fractal()
                ->collection($country->states)
                ->transformWith(new StateTransformer)
                ->toArray();

        return $states['data'];
      
    }

}

if (!function_exists('getCities')) {

	function getCities(Country $country, $state_id = null)
    {
        if($state_id) {
            return $country->cities()->where('state_id', $state_id)->get();
        }
        
        return $country->cities;
    }

}


if (!function_exists('unique_to_store')) {

	function unique_to_store($parameter, $value)
	{
		$ignore = array_has(array_filter($parameter), 2);
	       
		if($ignore) {

            if(DB::getSchemaBuilder()->hasColumn($parameter[0], 'deleted_at')) {
                return !(bool) DB::table($parameter[0])->where('store_id', session()->get('tenant'))
                                                        ->where($parameter[1], $value)
                                                        ->whereNull('deleted_at')
                                                        ->where('id', '<>', $parameter[2])
                                                        ->count();
            }

			return !(bool) DB::table($parameter[0])->where('store_id', session()->get('tenant'))
                                                    ->where($parameter[1], $value)
                                                    ->where('id', '<>', $parameter[2])
                                                    ->count();
		}

        if(DB::getSchemaBuilder()->hasColumn($parameter[0], 'deleted_at')) {
            return !(bool) DB::table($parameter[0])->where('store_id', session()->get('tenant'))
                                                    ->where($parameter[1], $value)
                                                    ->whereNull('deleted_at')
                                                    ->count();
        }

		return !(bool) DB::table($parameter[0])->where('store_id', session()->get('tenant'))->where($parameter[1], $value)->count();
	}
}

if (!function_exists('has_state')) {

    function has_state($parameters, $value)
    {   
        if($value) {
            return true;
        }

        return !(bool) Country::where($parameters[0], $parameters[1])->where('contains_states', 1)->count();
    }
}


if (!function_exists('unique_email')) {

	function unique_email($parameter, $value)
	{
		$ignore = array_has($parameter, 2);
	
		if($ignore) {
			return !(bool) DB::table($parameter[0])->where('is_guest', 0)->where($parameter[1], $value)->where('id', '<>', $parameter[2])->count();
		}
		
		return !(bool) DB::table($parameter[0])->where('is_guest', 0)->where($parameter[1], $value)->count();
	}
}

if (!function_exists('unique_domain')) {

    function unique_domain($parameter, $value)
    {
        $availability = (bool) Store::where('domain', $value)->count();
        $domains = (bool) RestrictedDomain::where('word', strtolower($value))->count();
        $result = true;
        
     
        if($availability === true && $domains === true) {
             $result = false;
        } elseif($availability === false && $domains === true) {
             $result = false;
        } elseif($availability === true && $domains === false) {
             $result = false;
        } 

        return $result;
        
    }
}

if (!function_exists('valid_rma')) {

    function valid_rma($data, $qty, $parameters)
    {
        $order = Order::where('id', $parameters[0])->first();
        $data = array_filter($data);

        if(!$data) {
            return false;
        }

        foreach ($data as $key => $value) {
            if(!$order->details()->where('id', $key)->count()) {
                return false;
            } else {
                $order_detail = $order->details()->where('id', $key)->first();
                if(!($value >= 0 && $value <= $order_detail->product_quantity)) {
                    return false;
                }
            }
        }

        return true;
        
    }
}

if (!function_exists('product_exists')) {

    function product_exists($value, $parameters)
    {
       
        if(count($parameters) && DB::table('products')->where('store_id', '=', session('store')->id)->where('id', $parameters[0])->count()) {
         
            $product = DB::table('products')->where('store_id', '=', session('store')->id)->where('id', $parameters[0])->first();

            if(DB::table('products')->where('products.store_id', session('store')->id)->where('products.id', $parameters[0])->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')->count()) {
                
                return (bool) DB::table('products')->where('products.store_id', session('store')->id)->where('products.id', $parameters[0])->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')->where('product_attributes.id', $value)->count();

            }

            return false;

        }
        
        return (bool) DB::table('products')->where('store_id', '=', session('store')->id)->where('id', $value)->count();
        
    }
}

if (!function_exists('product_variant')) {

    function product_variant($value, $parameters)
    {

        if(empty($value)) {
            return (bool) !DB::table('products')->where('products.store_id', session('store')->id)->where('products.id', $parameters[0])->join('product_attributes', 'products.id', '=', 'product_attributes.product_id')->count();
        }
  
        return true;
         
    }
}

if (!function_exists('product_eligible')) {

    function product_eligible($value)
    {  
        $product = DB::table('products')->where('store_id', '=', session('store')->id)->where('id', $value)->first();

        if($product && request()->hasCookie('cart')) {
            $cart = session('store')->carts()->where('reference', request()->cookie('cart'))->first();

            if($cart && $cart->items->count() && $cart->items->first()->product->shipping_class_id != $product->shipping_class_id) 
            {
                return false;
            }

        }

        return true;
        
    }
}


if (!function_exists('pattern_exists')) {

    function pattern_exists($attribute, $parameters, $validator)
    {
        $index = strpos($attribute, '.');
        $option_key = str_replace('*', $attribute[$index+1], $parameters[0]);
        $type_key = str_replace('*', $attribute[$index+1], $parameters[1]);
        $data = array_dot($validator->getData());
        $option_id = array_get($data, $option_key);
        $type = array_get($data, $type_key);

        if($type === 'pattern') {

            $attribute = Attribute::where('store_id', session()->get('tenant'))->where('id', $parameters[2])->first();

            return (bool) $attribute->options()->where('id', $option_id)->whereNotNull('pattern')->count();

        } 

        return true;

    }

}

if (!function_exists('validate_discount')) {

    function validate_discount($parameter, $value)
    {
        //return false;
        $valid = false;
        $discount = Discount::whereNull('store_id')->where('code', $value)->first();
        $billing = Billing::find($parameter[0]);
        if($discount) {

            if($discount->active) {

                if($discount->expires_at) {

                    if($discount->expires_at->greaterThanOrEqualTo(Carbon::now())) {

                        if($billing->amount >= $discount->minimum_amount) {

                            $valid = discountLimit($discount);
                        }

                    }

                } else {

                    if($billing->amount >= $discount->minimum_amount) {
                        $valid = discountLimit($discount);
                    }
                }
            } 

        } 
        
        return $valid;
        
    }
}

if (!function_exists('discountLimit')) {

    function discountLimit($discount)
    {
        $limit = false;

        if($discount->quantity == 0 && $discount->quantity_per_user == 0) {

            $limit = true;

        } else if($discount->quantity == 0 && $discount->quantity_per_user != 0) {

            $usage = session('store')->billings()->where('discount_id', $discount->id)->count();

            if($usage < $discount->quantity_per_user) {

                $limit = true;

            }

        } else if($discount->quantity != 0 && $discount->quantity_per_user == 0) {

            $usage = $discount->billings->count();

            if($usage < $discount->quantity) {

                $limit = true;

            }
        } else if($discount->quantity != 0 && $discount->quantity_per_user != 0) {

            $usage_per_user = session('store')->billings()->where('discount_id', $discount->id)->count();
            $usage = $discount->billings->count();

            if($usage_per_user < $discount->quantity_per_user && $usage < $discount->quantity) {

                $limit = true;
                
            }
        }

        return $limit;
    }
}

if (!function_exists('consignment_exists')) {

    function consignment_exists($value, $parameters)
    {
        if($value === 0) {
            return true;
        }

        if(ShippingZoneMethod::where('id', $value)->count()) {

            $shipping_method = ShippingZoneMethod::where('id', $value)->first();
            return $shipping_method->shippingZone->store_id === session('store')->id;
        }

        return false;
    }
}

if (!function_exists('isDirectory')) {

	/**
     * Determine if the given path is a directory.
     *
     * @param  string  $directory
     * @return bool
     */
   	function isDirectory($directory)
    {
        return is_dir($directory);
    }
}

if (!function_exists('makeDirectory')) {

	/**
     * Create a directory.
     *
     * @param  string  $path
     * @param  int     $mode
     * @param  bool    $recursive
     * @param  bool    $force
     * @return bool
     */
    function makeDirectory($path, $mode = 0755, $recursive = false, $force = false)
    {
        if ($force) {
            return @mkdir($path, $mode, $recursive);
        }
       
        return mkdir($path, $mode, $recursive);
    }
}


if (!function_exists('createDirectory')) {

	/**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    function createDirectory($path)
    {
        if (!isDirectory($path)) {
            makeDirectory($path, 0755, true);
        }

        return $path;
    }
}

if (!function_exists('copyDirectory')) {
    /**
     * Copy a directory from one location to another.
     *
     * @param  string  $directory
     * @param  string  $destination
     * @return bool
     */
    function copyDirectory($directory, $destination, $options = null)
    {

        if (! isDirectory($directory)) {
            return false;
        }

        $options = $options ?: FilesystemIterator::SKIP_DOTS;

        // If the destination directory does not actually exist, we will go ahead and
        // create it recursively, which just gets the destination prepared to copy
        // the files over. Once we make the directory we'll proceed the copying.
        if (! isDirectory($destination)) {
            makeDirectory($destination, 0755, true);
        }

        $items = new FilesystemIterator($directory, $options);

        foreach ($items as $item) {
            // As we spin through items, we will check to see if the current file is actually
            // a directory or a file. When it is actually a directory we will need to call
            // back into this function recursively to keep copying these nested folders.
            $target = $destination.'/'.$item->getBasename();

            if ($item->isDir()) {
                $path = $item->getPathname();

                if (! copyDirectory($path, $target, $options)) {
                    return false;
                }
            }

            // If the current items is just a regular file, we will just copy this to the new
            // location and keep looping. If for some reason the copy fails we'll bail out
            // and return false, so the developer is aware that the copy process failed.
            else {
                if (! copy($item->getPathname(), $target)) {
                    return false;
                }
            }
        }

        return true;
    }
}

if (!function_exists('generateInvoiceID')) {

    function generateInvoiceID()
    {
        $invoice_no = 1;

        if(BillingInvoice::latest('id')->count()) {
            $invoice_no = BillingInvoice::latest('id')->first()->id + 1;
        }

        return 'SB'.$invoice_no;
    }

}

if (!function_exists('generateStoreID')) {

    function generateStoreID(Store $store, $type)
    {
        $last_id = 0;

        if(Store::latest()->first()) {
            $last_id = Store::latest()->first()->id;
        }

        $id = (int) substr($last_id, 1) + 1;
        
        return (int) $type.$id;

    }

}

if (!function_exists('generateOrderID')) {

    function generateOrderID(Store $store)
    {
        $id = $store->orders->count();
        $order_id = '';

        do {

            $id++;
			$order_id = $id;

            if($store->setting->order_id_prefix && $store->setting->order_id_suffix) {
                $order_id = $store->setting->order_id_prefix.$id.$store->setting->order_id_suffix;
            }
			
            if($store->setting->order_id_prefix) {
                $order_id = $store->setting->order_id_prefix.$id;
            }

            if($store->setting->order_id_suffix) {
                $order_id = $id.$store->setting->order_id_suffix;
            }

        } while ((bool) session('store')->orders()->where('order_id', $order_id)->count());

        return $order_id;

    }

}

if (!function_exists('generateBillRef')) {

    function generateBillRef()
    {
        do {
            $ref = str_random(255);
        } while ((bool) Billing::where('reference', $ref)->count());

        return $ref;
    }

}

if(!function_exists('unlinkFile')) {

    function unlinkFile ($path_to_file)
    {
        if(file_exists($path_to_file) && !is_dir($path_to_file)) {
            if (!unlink($path_to_file)) {
              return 1;
            } else {
              return 0;
            }
        }
        
        return 0; 
    }

}

if(!function_exists('array_replace_null_values')) {

    function array_replace_null_values (Array $array)
    {
        foreach ($array as $key => $value) 
        {
            if(is_array($value))
                $array[$key] = array_replace_null_values($value);
            else
            {
                if (is_null($value))
                    $array[$key] = "";
            }
        }
        
        return $array;
    }

}
