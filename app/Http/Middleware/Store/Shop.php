<?php

namespace Shopbox\Http\Middleware\Store;

use Closure;
use Carbon\Carbon;
use Shopbox\Traits\Stock;
use Shopbox\Models\Front\Theme;
use Shopbox\Models\Zpanel\Store;
use Illuminate\Support\Facades\Auth;

class Shop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $previous_store = session('store');

        $response = initializeStore();

        if($response !== null) {
            return $response;
        }

        if(Auth::check() && $previous_store !== null && $previous_store->id !== session('store')->id) {
            Auth::logout();
            return redirect()->route('login');
        }

        if(!file_exists(resource_path('views/stores/'.session('store')->domain.'/'.session('store')->template->theme->slug.'/config/setting.json'))) {
            exit('<pre style="word-wrap: break-word; white-space: pre-wrap;">Theme settings file not found.</pre>');
        }

        $config = json_decode(file_get_contents(resource_path('views/stores/'.session('store')->domain.'/'.session('store')->template->theme->slug.'/config/setting.json')), true);

        $request->merge(['viewPath'=> 'stores.'.session('store')->domain.'.'.session('store')->template->theme->slug.'.pages']);
        $request->merge(['settings'=> $config['current']]);
        $request->merge(['themePath'=> 'stores.'.session('store')->domain.'.'.session('store')->template->theme->slug]);

        if($request->has('theme_id') && $request->has('preview')) {

            $store_theme = session('store')->themes()->whereHas('theme', function ($query) use ($request) {
                    return $query->where('alias', $request->theme_id);
            })->first();
           
            if($store_theme) {
            
                $request->merge(['viewPath'=> 'stores.'.session('store')->domain.'.'.$store_theme->theme->slug.'.pages']);
                $request->merge(['settings'=> $request->session()->get('settings')]);
                $request->merge(['themePath'=> 'stores.'.session('store')->domain.'.'.$store_theme->theme->slug]);

            }

        }

        $uniq_token = $visit_token  = $user_currency = '';

        if(!$request->cookie('uniq_token')) {
            $uniq_token = cookie('uniq_token', str_random(36), 525600 * 2, '/', getStoreDomain(session('store')));
        }

        if(!$request->cookie('visit_token')) {
            $visit_token = cookie('visit_token', str_random(36), 1440, '/', getStoreDomain(session('store')));
        }

        if(!$request->cookie('user_currency')) {
            $user_currency = cookie('user_currency', session('store')->setting->currency->iso_code, 1440 * 14, '/', getStoreDomain(session('store')), false, false);
        }

        if($request->hasCookie('cart') && session('store')->setting->reservation_time > 0) {

            $cart = session('store')->carts()->where('reference', $request->cookie('cart'))->first();

            if($cart && $cart->stock_reserved && Carbon::now()->greaterThan($cart->reservation_ends_at)) {
                $cart->stock_reserved = 0;
                $cart->save();
                $this->restock();
            }
        }

        $request->session()->put('schema', []);
    
        if($uniq_token && $visit_token && $user_currency) {
            return $next($request)->cookie($uniq_token)->cookie($visit_token)->cookie($user_currency);
        } else if($uniq_token && $visit_token) {
            return $next($request)->cookie($uniq_token)->cookie($visit_token);
        } else if($visit_token && $user_currency) {
            return $next($request)->cookie($visit_token)->cookie($user_currency);
        } else if($uniq_token && $user_currency) {
            return $next($request)->cookie($uniq_token)->cookie($user_currency);
        } else if($user_currency) {
            return $next($request)->cookie($user_currency);
        } else if($uniq_token) {
            return $next($request)->cookie($uniq_token);
        } else if($visit_token) {
            return $next($request)->cookie($visit_token);
        } 
        
        return $next($request);

    }

}