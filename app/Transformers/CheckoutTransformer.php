<?php

namespace Shopbox\Transformers;

use Carbon\Carbon;
use Shopbox\Traits\Taxable;
use Shopbox\Traits\Consignment;
use Shopbox\Traits\Discountable;
use Illuminate\Support\Collection;
use Modules\Product\Entities\Cart;
use Modules\Customer\Entities\Address;
use League\Fractal\TransformerAbstract;
use Modules\Discount\Entities\Discount;
use Modules\Product\Entities\CartCarrier;
use Shopbox\Transformers\Cart\CartTransformer;
use Modules\Product\Entities\ShippingZoneMethod;
use Shopbox\Transformers\Cart\ProductTransformer;
use Shopbox\Transformers\Checkout\AddressTransformer;
use Shopbox\Transformers\Checkout\CustomerTransformer;

class CheckoutTransformer extends TransformerAbstract
{

    use Discountable, Taxable, Consignment;

    protected $discount_error;

    public function __construct($error = null)
    {
        $this->discount_error = $error;
    }

	public function transform(Cart $cart)
	{
        $cart_data = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];

        return [
            'id' => $cart->reference,
            'order_id' => $cart->order ? $cart->order->order_id : '',
            'cart' => $cart_data,
            'billing_address' => $cart->invoice_address ? fractal()->item($cart->invoice_address)->transformWith(new AddressTransformer)->toArray()['data'] : '',
            'shipping_address' => $cart->delivery_address ? fractal()->item($cart->delivery_address)->transformWith(new AddressTransformer)->toArray()['data'] : '',
            'note' => $cart->message,
            'customer' => fractal()->item($cart->customer)->transformWith(new CustomerTransformer)->toArray()['data'],
            'buyer_accepts_marketing' => $cart->customer ? (bool) $cart->customer->newsletter : false,
            'subtotal' => $cart_data['original_total_price'],
            'tax' => [
                'name' => 'Tax',
                'amount' => $this->getTaxRate($this->getTaxRule($cart), $cart)
            ],
            'surcharge' => $this->getSurcharge($cart), 
            'grand_total' => $this->getCartTotal($cart, $cart_data),
            'consignment' => $this->getCarrier($cart, $cart_data),
            'reservation_time' => session('store')->setting->reservation_time > 0 ? Carbon::now()->toDateTimeString() : null,
            'reservation_time_left' => $cart->reservation_ends_at ? $cart->reservation_ends_at->toDateTimeString() : 0
        ];
	}

    protected function getCartTotal(Cart $cart, $cart_data)
    {
        $total = $cart_data['total_price'] + $this->getTaxRate($this->getTaxRule($cart), $cart) + $this->getSurcharge($cart);

        if(count($this->getCarrier($cart, $cart_data)) > 0) {
            $total += $this->getCarrier($cart, $cart_data)['rate'];
        }

        return $total;
    }

    protected function getSurcharge(Cart $cart)
    {
        $surcharge = 0;

        if($cart->carrier && $cart->carrier->carrier && $cart->carrier->carrier->shippingZone->cod) {

            $surcharge = $cart->carrier->carrier->shippingZone->cod->surcharge;

        }

        return (float) $surcharge;
    }

    protected function getCarrier(Cart $cart, $basket)
    {
        $carrier = [];

        if(!$basket['need_shipping']) {
            return $carrier;
        }

        if($cart->carrier) {

            $carrier['id'] = $cart->carrier->carrier_id ?: 0;
            $carrier['image'] = '';
            $carrier['name'] = $cart->carrier->name;
            $carrier['rate'] = (float) $cart->carrier->shipping_cost;

        } elseif(array_key_exists(0,$this->getShippingQuotes($cart, $this->getShippingZones($cart)))) {

            $carrier = $this->getShippingQuotes($cart, $this->getShippingZones($cart))[0];
            $this->addDefaultCarrier($cart, $carrier);
        }  

        $cart->refresh();
        return $carrier;
    }

    protected function addDefaultCarrier(Cart $cart, $consignment)
    {
        $type = 'shipping_storepickup';
        $method = ShippingZoneMethod::find($consignment['id']);

        if($method) {
            $type = $method->shippingMethod->alias;
        }

        $carrier = $cart->carrier ?: new CartCarrier;
        $carrier->cart()->associate($cart);

        if($method) {
            $carrier->carrier()->associate($method);
            $carrier->shipping_cost = $consignment['rate'];
        } else {
            $carrier->carrier_id = null;
            $carrier->shipping_cost = 0;
        }
        
        $carrier->name = $consignment['name'];
        $carrier->type = $type;
        if(!$cart->carrier) {
            $carrier->created_at_tz = $carrier->freshTimestamp()->timezone($cart->store->setting->timezone->timezone);
        }
        $carrier->updated_at_tz = $carrier->freshTimestamp()->timezone($cart->store->setting->timezone->timezone);
        $carrier->save();

    }

	protected function discount($cart)
	{
		$discount = 0.00;
        $cart->load('discount');
        $data = [];
        
		if($cart->discount) {
            $discount = $this->getDiscount($cart, $cart->discount->discount->code);
            $data['value'] = $discount['amount'];
            $data['name'] = $cart->discount ? $cart->discount->discount->name : '';
            $data['error'] = $discount['error'];
        } else {
            $data['value'] = $discount;
            $data['name'] = '';
            $data['error'] = $this->discount_error;
        }
   
        return $data;

	}

	protected function getPaymentOptions(Cart $cart, Collection $payments)
    {
        $payment_options = [];

        if($payments->count()) {
            foreach($payments as $key => $payment) {
                if($payment->plugin->alias !== 'cashondelivery') {

                    $payment_options[$key]['id'] = $payment->id;
                    $payment_options[$key]['name'] = $payment->display_name;
                    $payment_options[$key]['rate'] = '';

                } else {
                	if($cart->carrier && $cart->carrier->shippingZone->cod && $cart->carrier->shippingZone->cod->status && $cart->carrier->shippingMethod->id != 4) {
						$payment_options[$key]['id'] = $payment->id;
						$payment_options[$key]['name'] = $payment->display_name;
						$payment_options[$key]['rate'] = $cart->carrier->shippingZone->cod->surcharge;
			    	}

                }
            }
        }
    	
        return array_values($payment_options);
    }

	protected function getAddress(Address $address)
	{
		$data = [];
		$data['first_name'] = $address->firstname;
		$data['last_name'] = $address->lastname;
		$data['address1'] = $address->address;
		$data['address2'] = $address->address2;
		$data['country'] = $address->country_id;
		$data['country_name'] = $address->country->name;
		$data['state'] = $address->state_id;
		$data['state_name'] = $address->state ? $address->state->name : '';
        $data['city'] = $address->city;
		//$data['city_name'] = $address->city->city_name;
		$data['postcode'] = $address->zip_code;
		$data['phone'] = $address->phone;

		return $data;
	}

	protected function sameAddress(Cart $cart)
	{
		 $checkout_session = json_decode($cart->checkout_session_data, true);

		 return $checkout_session['checkout-addresses-step']['use_same_address'];
	}
	
}