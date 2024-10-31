<?php

namespace Shopbox\Http\Controllers\Zpanel\POS;

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use Shopbox\Models\Zpanel\Country;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\CartProduct;
use Modules\Customer\Entities\Guest;
use Shopbox\Transformers\Cart\ProductTransformer;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class CartController extends Controller
{
    protected $geoip;

    public function __construct()
    {
        $this->geoip = geoip()->getLocation(geoip()->getClientIP());
    }

    protected function createAnonymousUser() 
    {
        $dd = new DeviceDetector($_SERVER['HTTP_USER_AGENT']);
        $dd->parse();
        $client_info = $dd->getClient();
        $os_info = $dd->getOs();

        $guest = new Guest;
        $guest->ip_address = $this->geoip->ip;
        $guest->browser = $client_info['name'];
        $guest->browser_version = $client_info['version'];
        $guest->device = $dd->getDeviceName();
        $guest->platform = $os_info['name'];
        $guest->platform_version = $os_info['version'];

        if(Country::where('iso_code', $this->geoip->iso_code)->count()) {

           $country = Country::where('iso_code', $this->geoip->iso_code)->first();

           if($country) {
            $guest->country()->associate($country);
           }
           

        }

        $guest->state = $this->geoip->state_name;
        $guest->city = $this->geoip->city;
        $guest->ip_address = $this->geoip->ip;
        $guest->save();

        return $guest;
    }

    protected function createCart()
    {
        $cart = new Cart();
        $cart->guest()->associate($this->createAnonymousUser());
        $cart->reference = str_random(32);
        $cart->store()->associate(session('store'));
        $cart->currency()->associate(session('store')->setting->currency->id);
        $cart->created_at_tz = $cart->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $cart->updated_at_tz = $cart->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $cart->save();

        return $cart;
    }

    protected function createCartItem(Cart $cart, $product_id, $qty, $attribute_id = null)
    {
        $product = new CartProduct;

        if($cart->items->count()) {

            if($attribute_id) {

              if($cart->items()->where('product_id',$product_id)->where('product_attribute_id', $attribute_id)->count()) {
             
                $product = $cart->items()->where('product_id',$product_id)->where('product_attribute_id', $attribute_id)->first();
                
              }

            } else {

              if($cart->items()->where('product_id',$product_id)->count()) {
             
                $product = $cart->items()->where('product_id',$product_id)->first();
                
              }

            }
            
        }  
           
        $product->store()->associate(session('store'));
        $product->cart()->associate($cart);
        $product->product()->associate($product_id);
        if($attribute_id)
          $product->product_attribute()->associate($attribute_id);
        $product->quantity = $qty;
        $product->save();

        return $product;
    }

    public function store(Request $request)
    {
        $cart = Cart::where('reference', $request->cookie('cart'))->first();

        if(!$cart) {
            $cart = $this->createCart();
            $cookie = cookie('cart', $cart->id, time() + (86400 * 30), '/', null, false, false);
        }

        $product = $this->createCartItem($cart, $request->id, $request->quantity, $request->attribute_id);

        $product = fractal()->item($product)->transformWith(new ProductTransformer)->toArray();

        return response()->json(compact('product'))->cookie($cookie);

    }

}
