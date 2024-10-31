<?php

namespace Shopbox\Http\Controllers\Front;

use Carbon\Carbon;
use Shopbox\Traits\Stock;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;
use Shopbox\Http\Requests\Checkout\CustomerFormRequest;
use Shopbox\Http\Requests\Checkout\AuthFormRequest;
use Shopbox\Http\Requests\Checkout\AddressFormRequest;
use Shopbox\Http\Requests\Checkout\ShippingFormRequest;
use Shopbox\Models\Zpanel\Track;
use Shopbox\Models\Zpanel\State;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Models\Zpanel\City;
use Shopbox\Models\Zpanel\Plugin;
use Modules\Product\Entities\ShippingZoneLocation;
use Modules\Product\Entities\ShippingZoneMethod;
use Modules\Product\Entities\TaxRule;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\CartCarrier;
use Modules\Product\Entities\CheckoutOtp;
use Modules\Product\Entities\TaxOption;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\Address;
use Shopbox\Transformers\CheckoutTransformer;
use Shopbox\Transformers\Checkout\CustomerTransformer;
use Shopbox\Transformers\Cart\CartTransformer;
use Shopbox\Transformers\Cart\ProductTransformer;
use Modules\Customer\Transformers\AddressCollectionTransformer;
use Modules\Customer\Transformers\AddressTransformer;
use Modules\Product\Entities\CartDiscount;
use Modules\Discount\Entities\Discount;
use Shopbox\Traits\Discountable;
use Shopbox\Traits\Consignment;
use Illuminate\Support\Facades\Auth;
use Shopbox\Transformers\Checkout\PaymentTransformer;



class CheckoutController extends Controller
{
    use Discountable, Consignment, Stock;

    protected $redirectTo = '/checkout';

    public function __construct()
    {
        $this->agent = new Agent(); 
    }

    public function index(Request $request)
    {   
        $countries = Country::where('status', 1)->orderBy('name', 'asc')->get(['id', 'name'])->toJson();
        $checkout = $request->cookie('cart');
        $cart = session('store')->carts()->where('reference', $request->cookie('cart'))->first();
        $checkout_session = $cart->checkout_session_data;

        return view('layouts.checkout', compact('checkout_session', 'checkout'));
    }

    public function show(Request $request, Cart $cart) 
    {

        return fractal()
                ->item($cart)
                ->transformWith(new CheckoutTransformer)
                ->toArray()['data'];
    }

    public function authenticate(AuthFormRequest $request)
    {
  
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_guest' => 0])) {

            $cart = session('store')->carts()->where('reference', $request->cookie('cart'))->first();

            if($cart) {

                $cart->customer()->associate(auth()->user());
                $checkout_session = json_decode($cart->checkout_session_data, true);
                $checkout_session['checkout-customer-step']['step_is_complete'] = true;
                $checkout_session['checkout-addresses-step']['step_is_reachable'] = true;
                $cart->checkout_session_data = json_encode($checkout_session);
                $cart->save();

            }

            $customer = fractal()
                    ->item(auth()->user())
                    ->transformWith(new CustomerTransformer)
                    ->toArray()['data'];

            return response()->json(compact('customer'));
        }

        $errors = ['error' => trans('auth.failed')];

        return response()->json($errors, 422);

    }

    public function customer(Cart $cart, CustomerFormRequest $request)
    {
        
        $checkout_session = json_decode($cart->checkout_session_data, true);
        $checkout_session['checkout-customer-step']['step_is_complete'] = true;
        $checkout_session['checkout-addresses-step']['step_is_reachable'] = true;

        $customer = Customer::create([
            'firstname' => '',
            'lastname' => '',
            'email_guest' => $request->email,
            'newsletter' => $request->newsletter,
            'is_guest' => 1,
            'ip_address' => $request->ip(),
            'browser' => $this->agent->browser(),
            'platform' => $this->agent->platform(),
        ]);

        $cart->customer()->associate($customer);
        $cart->checkout_session_data = json_encode($checkout_session);
        $cart->save();

        return fractal()
                ->item($cart)
                ->transformWith(new CheckoutTransformer)
                ->toArray()['data'];

    }

    public function address(Cart $cart, AddressFormRequest $request)
    {
        $checkout_session = json_decode($cart->checkout_session_data, true);
        $cart_data = fractal()
                    ->item($cart)
                    ->transformWith(new CartTransformer)
                    ->toArray()['data'];

        if($request->same_address === 'true') {
            $billing_address = $this->saveAddress($cart, $request->billing, 'billing');
            $shipping_address = $billing_address;
            $checkout_session['checkout-addresses-step']['use_same_address'] = true;

        } else {
            $billing_address = $this->saveAddress($cart, $request->billing, 'billing');
            $shipping_address = $this->saveAddress($cart, $request->shipping, 'shipping');
            $checkout_session['checkout-addresses-step']['use_same_address'] = false;
        }

        $checkout_session['checkout-addresses-step']['step_is_complete'] = true;

        if($cart_data['need_shipping']) {
            $checkout_session['checkout-shipping-step']['step_is_reachable'] = true;
        } else {
            $checkout_session['checkout-shipping-step']['step_is_complete'] = true;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = true;
        }
       

        $cart->checkout_session_data = json_encode($checkout_session);
        $cart->invoice_address()->associate($billing_address);
        $cart->delivery_address()->associate($shipping_address);
        $cart->save(); 
        
        // fire and forget
        $this->sendOTP($cart);  

        return fractal()
                ->item($cart)
                ->transformWith(new CheckoutTransformer)
                ->toArray();

    }


    protected function sendOTP(Cart $cart) {

        $otp = new CheckoutOtp();
        $otp->generateOtp();
        var_dump($otp);
        // Send OTP to the customer's email
        Mail::to($cart->customer->customerEmail)->queue(new OtpEmail($otp->otp_code, $cart->customer->customerEmail));
    } 
    
    protected function saveAddress(Cart $cart, $data, $type)
    {
        if($type === 'shipping') {
 
            if($cart->delivery_address) {
                $address = $cart->delivery_address;
            } else {
                $address = new Address;
            }

        } else if ($type === 'billing') {

            if($cart->invoice_address) {
                $address = $cart->invoice_address;
            } else {
                $address = new Address;
            }

            if(!auth()->user()) {
                $cart->customer->timestamps = false;
                $cart->customer->firstname = $data['firstname'];;
                $cart->customer->lastname = $data['lastname'];
                $cart->customer->phone = $data['phone'];
                $cart->customer->save();
            }
            
            
        } 

        $address->customer()->associate($cart->customer->id);
        $country = Country::where('iso_code', $data['country'])->first();
        $address->country()->associate($country);

        if(array_has($data, 'state')) {
            $state = State::where('iso_code', $data['state'])->first();
            $address->state()->associate($state);
        }

        $address->firstname = $data['firstname'];
        $address->lastname = $data['lastname'];
        $address->phone = $data['phone'];

        if(array_has($data, 'address2')) {
            $address->address2 = $data['address2'];
        }

        $address->address = $data['address1'];
        $address->city = $data['city'];
        $address->zip_code = $data['postcode']; 
        $address->save();

        return $address;
    }

    public function shipping(Cart $cart, ShippingFormRequest $request)
    {
        $basket = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];
        $name = $cart->store->setting->store_pickup_display_name;
        $type = 'shipping_storepickup';

        $method = ShippingZoneMethod::find($request->shipping_id);

        if($method) {
            $name = $method->display_name;
            $type = $method->shippingMethod->alias;
        }

        $carrier = $cart->carrier ?: new CartCarrier;
        $carrier->cart()->associate($cart);

        if($method) {
            $carrier->carrier()->associate($request->shipping_id);
            $carrier->shipping_cost = $method->rate($basket['total_price'], $basket['item_count'], $basket['total_weight']);
        } else {
            $carrier->carrier_id = null;
            $carrier->shipping_cost = 0;
        }
        
        $carrier->name = $name;
        $carrier->type = $type;
        $carrier->created_at_tz = $carrier->freshTimestamp()->timezone($cart->store->setting->timezone->timezone);
        $carrier->updated_at_tz = $carrier->freshTimestamp()->timezone($cart->store->setting->timezone->timezone);
        $carrier->save();
        
        $cart = $cart->fresh();

        return fractal()
                ->item($cart)
                ->transformWith(new CheckoutTransformer)
                ->toArray();

    }

    public function payment(Cart $cart, Request $request)
    {
        $cart->payment_method = $request->payment_method;
        $cart->save();
        $cart = $cart->fresh();

        return fractal()
                ->item($cart)
                ->transformWith(new CheckoutTransformer)
                ->toArray();
    }

    public function update(Cart $cart, Request $request)
    {
        $checkout_session = json_decode($cart->checkout_session_data, true);
        $checkout_session['checkout-shipping-step']['step_is_complete'] = true;
        $checkout_session['checkout-payment-step']['step_is_reachable'] = true;
        $cart->checkout_session_data = json_encode($checkout_session);
    
        if($request->note) {
            $cart->message = $request->note;
        }

        $cart->save();
    }

    public function discount(Cart $cart, Request $request) 
    {   
        $this->validate($request, [
            'discount_code' => 'required'
        ], [
            'discount_code.required' => 'Coupon code is required.'
        ]);

        $data = $this->addDiscount($cart, $request->discount_code);
   
        if($data['status'] == 400) {
            return response()->json(compact('data'), 422);
        }

    }


    public function removeDiscount(Cart $cart, Discount $discount, Request $request) 
    {

        $cart->discounts()->where('discount_id', $discount->id)->delete();

        return fractal()
                ->item($cart)
                ->transformWith(new CheckoutTransformer)
                ->toArray();
        
    }

    public function payments(Request $request)
    {
        if(!$request->cookie('cart')) {
            abort('404');
        }

        $payments = session('store')->payments()->where('active', 1)->orderBy('id', 'desc')->get();

        $payments = fractal()
                ->collection($payments)
                ->transformWith(new PaymentTransformer)
                ->toArray();

        return array_values(array_filter($payments['data']));

    }

    public function shippingRates(Cart $cart, Request $request)
    {
        if($cart->requiredShipping()) {
            return $this->getShippingQuotes($cart, $this->getShippingZones($cart));
        }
        return [];
    }

    public function getCities(Request $request) 
    {
        $cities = [];
        $country = Country::find($request->country);

        if($country && $request->state) {
            $cities = getCities($country, $request->state);
        } elseif($country) {
            $cities = getCities($country);
        }
        

        return response()->json(compact('cities'));
    }

    public function getCityPostCode(Request $request)
    {
        $postcode = '';
        $data = explode('|', $request->city);
        $city = City::find($data[0]);

        if($city) {
            $postcode = $city->zip_code;
        }
        

        return response()->json(compact('postcode'));
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $cart = Cart::where('reference', $request->cookie('cart'))->first();

        if($cart) {
            $cart->customer()->dissociate();
            $cart->checkout_session_data = '{"checkout-customer-step":{"step_is_reachable":true,"step_is_complete":false},"checkout-addresses-step":{"step_is_reachable":false,"step_is_complete":false,"use_same_address":true},"checkout-shipping-step":{"step_is_reachable":false,"step_is_complete":false},"checkout-payment-step":{"step_is_reachable":false,"step_is_complete":false}}';
            $cart->save();

            if($cart->discounts->count()) {
                $cart->discounts->detach();
            }
        }

    }

    public function destroy(Cart $cart, Request $request)
    {
        $this->validate($request, [
            'products' => 'required|array'
        ]);

        $items = fractal()
                ->collection($cart->items)
                ->transformWith(new ProductTransformer)
                ->toArray()['data'];

        foreach ($request->products as $product_id) {
            
            $item = $cart->items()->where('id', $product_id)->first();

            if($item) {

                $item = fractal()
                        ->item($item)
                        ->transformWith(new ProductTransformer)
                        ->toArray()['data'];

                if($item['requires_splitting'] || $item['stock_count'] === 0) {
        
                    $cart->items()->where('id', $item['id'])->delete();

                }

            }

        }
    }

    public function getAddresses ()
    {
        if(!auth()->check()) {
            return;
        }
        return  fractal()
                ->collection(auth()->user()->addresses)
                ->transformWith(new AddressCollectionTransformer)
                ->toArray();
    }

    public function getAddress (Request $request)
    {
        $address = Address::find($request->address_id);

        if(!$address) {
            return;
        }

        return  fractal()
                ->item($address)
                ->transformWith(new AddressTransformer)
                ->toArray();
    }

}
