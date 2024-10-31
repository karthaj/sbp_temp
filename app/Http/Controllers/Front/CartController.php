<?php

namespace Shopbox\Http\Controllers\Front;

use Carbon\Carbon;
use Shopbox\Traits\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Shopbox\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;
use Modules\Product\Entities\StoreStock;
use Shopbox\Transformers\Cart\CartTransformer;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\CartProduct;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Combination;
use Modules\Customer\Entities\Guest;
use Shopbox\Models\Zpanel\Track;
use Shopbox\Transformers\Cart\ProductTransformer;
use Shopbox\Http\Requests\Cart\CartFormRequest;
use Shopbox\Http\Requests\Cart\CartUpdateFormRequest;

class CartController extends Controller
{
    use Stock;

    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        /*if(auth()->check()) {
            $cart = auth()->user()->carts()->where('id', $request->cookie('cart'))->first();
        } else {
            $cart = Cart::find($request->cookie('cart'));
        }*/

        // $cart = Cart::where('reference', $request->cookie('cart'))->first();

        // return fractal()->item($cart)->transformWith(new CartTransformer)->toArray();

        return view($request->viewPath.'.cart');
    }

   
    protected function createCart(Guest $guest = null)
    {
        $cart = new Cart();

        if($guest) {
          $cart->guest()->associate($guest);
        }

        $cart->reference = str_random(32);
        $cart->store()->associate(session('store'));
        $cart->currency()->associate(session('store')->setting->currency->id);
        $cart->created_at_tz = $cart->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
        $cart->updated_at_tz = $cart->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);

        if(auth()->check()) {

          $cart->customer()->associate(auth()->user());
          $cart->checkout_session_data = '{"checkout-customer-step":{"step_is_reachable":true,"step_is_complete":true},"checkout-addresses-step":{"step_is_reachable":true,"step_is_complete":false,"use_same_address":true},"checkout-shipping-step":{"step_is_reachable":false,"step_is_complete":false},"checkout-payment-step":{"step_is_reachable":false,"step_is_complete":false}}';

        } else {

           $cart->checkout_session_data = '{"checkout-customer-step":{"step_is_reachable":true,"step_is_complete":false},"checkout-addresses-step":{"step_is_reachable":false,"step_is_complete":false,"use_same_address":true},"checkout-shipping-step":{"step_is_reachable":false,"step_is_complete":false},"checkout-payment-step":{"step_is_reachable":false,"step_is_complete":false}}';

        }
       
        $cart->save();

        return $cart;
    }

    protected function createCartItem(Cart $cart, $product_id, $qty, $attribute_id = null)
    {
        
        if($cart->items->count()) {

            if($attribute_id) {

              if($cart->items->where('product_id',$product_id)->where('product_attribute_id', $attribute_id)->count()) {;
                $item = $cart->items->where('product_id',$product_id)->where('product_attribute_id', $attribute_id)->first();
                $item->quantity += $qty;
                $item->save();
                return $item;
              }
      
            } else {

              if($cart->items->where('product_id',$product_id)->count()) {
             
                $item = $cart->items->where('product_id',$product_id)->first();
                $item->quantity += $qty;
                $item->save();
                return $item;
              }

            }
            
        }  

        $item = new CartProduct;
        $item->store()->associate(session('store'));
        $item->cart()->associate($cart);
        $item->product()->associate($product_id);

        if($attribute_id) {

          $item->product_attribute()->associate($attribute_id);

          if(session('store')->onlineStore && session('store')->onlineStore->stocks->where('stock_id', $item->product_attribute->stock->id)->count()) {

            $stock = session('store')->onlineStore->stocks->where('stock_id', $item->product_attribute->stock->id)->first();
            $item->stock()->associate($stock);

          } else {

            $item->stock()->associate($this->createStock($product_id, $attribute_id));

          }

        }
        else {

          if(session('store')->onlineStore && session('store')->onlineStore->stocks->where('stock_id', $item->product->stock->id)->count()) {

            $stock = session('store')->onlineStore->stocks->where('stock_id', $item->product->stock->id)->first();
            $item->stock()->associate($stock);

          } else {

            $item->stock()->associate($this->createStock($product_id));

          }
          
        }

        $item->quantity = $qty;
        $item->save();

        return $item;
    }

    protected function createStock($product_id, $attribute_id = null)
    {
      $stock = '';

      if($attribute_id) {

        $stock = session('store')->stocks()->where('product_id', $product_id)->where('product_attribute_id', $attribute_id)->first();

      } else {

        $stock = session('store')->stocks()->where('product_id', $product_id)->first();

      }

      if(!$stock || !session('store')->onlineStore) {
        abort('404');
      }

      $store_stock = new StoreStock;
      $store_stock->store()->associate(session('store'));
      $store_stock->stock()->associate($stock);
      $store_stock->storeLocation()->associate(session('store')->onlineStore);
      $store_stock->created_at_tz = $store_stock->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
      $store_stock->updated_at_tz = $store_stock->freshTimestamp()->timezone(session('store')->setting->timezone->timezone);
      $store_stock->save();

      return $store_stock;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CartFormRequest $request)
    {
        if (auth()->check()) {

            if(auth()->user()->carts->count()) {
               
                if(auth()->user()->carts()->where('reference', $request->cookie('cart'))->count()) {
                    $cart = auth()->user()->carts()->where('reference', $request->cookie('cart'))->first();
                } else {
                    
                    $cart = $this->createCart();
                }
            } else {
               
              $cart = $this->createCart();
            }

            if($cart->stock_reserved) {
              $cart->stock_reserved = 0;
              $cart->save();
              $this->restock($cart);

              if($cart->discounts->count()) {
                  $cart->discounts->detach();
              }

            }
            
            $item = fractal()->item($this->createCartItem($cart, $request->product_id, $request->qty, $request->attribute_id))->transformWith(new ProductTransformer)->toArray()['data'];

            if($item && $item['need_shipping'] && $cart->carrier) {
              $cart->carrier()->delete();

              $checkout_session = json_decode($cart->checkout_session_data, true);
              $checkout_session['checkout-shipping-step']['step_is_complete'] = false;
              $checkout_session['checkout-shipping-step']['step_is_reachable'] = true;
              $checkout_session['checkout-payment-step']['step_is_complete'] = false;
              $checkout_session['checkout-payment-step']['step_is_reachable'] = false;
              $cart->checkout_session_data = json_encode($checkout_session);

              $cart->save();
            }

            $cookie = cookie('cart', $cart->reference, 1440 * 14, '/', config('session.domain'), false, false);

            return response()->json(compact('item'))->cookie($cookie);

        } else {

            $cart = session('store')->carts()->where('reference', $request->cookie('cart'))->first();

            if($cart == '') {
              $guest = new Guest;
              $guest->ip_address = Track::getRealIpAddr();
              $guest->browser = $this->agent->browser();
              $guest->platform = $this->agent->platform();
              $guest->save();

              $cart = $this->createCart($guest);
             
            }

            if($cart->stock_reserved) {
              $cart->stock_reserved = 0;
              $cart->save();

              $this->restock($cart);

              if($cart->discounts->count()) {
                  $cart->discounts->detach();
              }

            }
    
            $item = fractal()->item($this->createCartItem($cart, $request->product_id, $request->qty, $request->attribute_id))->transformWith(new ProductTransformer)->toArray()['data'];

            if($item && $item['need_shipping'] && $cart->carrier) {
              $cart->carrier()->delete();

              $checkout_session = json_decode($cart->checkout_session_data, true);
              $checkout_session['checkout-shipping-step']['step_is_complete'] = false;
              $checkout_session['checkout-shipping-step']['step_is_reachable'] = true;
              $checkout_session['checkout-payment-step']['step_is_complete'] = false;
              $checkout_session['checkout-payment-step']['step_is_reachable'] = false;
              $cart->checkout_session_data = json_encode($checkout_session);

              $cart->save();
            }

            $cookie = cookie('cart', $cart->reference, 1440 * 14, '/', config('session.domain'), false, false);

            return response()->json(compact('item'))->cookie($cookie);

        }
    }


    public function show(Request $request)
    {
        $cart = session('store')->carts()->where('reference', $request->cookie('cart'))
                ->with(['currency' => function ($query) {
                  $query->select('id', 'iso_code');
                }, 'customer' => function($query) {
                  $query->select('id', 'email', 'email_guest');
                }, 'items.product_attribute', 
                'items.product.images',
                'items.product.stock.storeStocks',
                'items.product_attribute.stock.storeStocks', 
                'items.cart.discounts'])
                ->select('id', 'reference', 'currency_id', 'customer_id')
                ->first();
        // dd($cart);
        if($cart) {
          return fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];
        }

        return [];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CartUpdateFormRequest $request)
    {

      if(auth()->check()) {
          $cart = auth()->user()->carts()->where('reference', $request->cookie('cart'))->first();
      } else {
          $cart = session('store')->carts()->where('reference', $request->cookie('cart'))->first();
      }

      if($cart) {

        if($cart->stock_reserved) {
          $cart->stock_reserved = 0;
          $cart->save();
          $this->restock($cart);

          if($cart->discounts->count()) {
              $cart->discounts->detach();
          }
              
        }

        if($request->qty !== 0 && $cart->items()->where('id', $request->id)->count()) {

          $cart->items()->where('id', $request->id)->update([
            'quantity' => $request->qty
          ]);

        } else {

          $cart->items()->where('id', $request->id)->delete();

        }

        if($cart->carrier) {
            $cart->carrier()->delete();

            $checkout_session = json_decode($cart->checkout_session_data, true);
            $checkout_session['checkout-shipping-step']['step_is_complete'] = false;
            $checkout_session['checkout-shipping-step']['step_is_reachable'] = true;
            $checkout_session['checkout-payment-step']['step_is_complete'] = false;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = false;
            $cart->checkout_session_data = json_encode($checkout_session);

            $cart->save();
        }

      }

    }

    
    public function recover(Cart $cart)
    {
        if(!$cart) {
          return redirect()->route('cart.index')->withError('unable to recover your cart.');
        }

        $cookie = cookie('cart', $cart->reference, 1440 * 14, '/', config('session.domain'), false, false);

        return redirect()->route('cart.index')->cookie($cookie);

    }


    public function reserveStock(Cart $cart)
    {
      $cart_data = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];

      if(!$cart_data['inventory_check']) {
        return redirect()->route('cart.index');
      }

      if(!$cart->stock_reserved) {
          $this->reserve($cart);
          $cart->stock_reserved = 1; 
          if(session('store')->setting->reservation_time > 0) {
              $cart->reservation_ends_at = Carbon::now()->addMinutes(session('store')->setting->reservation_time);
          }     

          $cart->save();

      } 
    
      return redirect()->route('checkout.index');

    }

}
