<?php

namespace Shopbox\Transformers\Checkout;

use League\Fractal\TransformerAbstract;
use Illuminate\Support\Collection;
use Modules\Product\Entities\Cart;
use Modules\Customer\Entities\Address;
use Modules\Discount\Entities\Discount;
use Shopbox\Traits\Discountable;
use Shopbox\Traits\Taxable;
use Shopbox\Transformers\Cart/CartTransformer;
use Shopbox\Transformers\Cart\AddressTransformer;
use Shopbox\Transformers\Cart\CustomerTransformer;

class CheckoutTransformer extends TransformerAbstract
{

    use Discountable, Taxable;

    protected $discount_error;

    public function __construct($error = null)
    {
        $this->discount_error = $error;
    }

	public function transform(Cart $cart)
	{
		// return [
		// 	'customer' => [
		// 		'email' => $cart->customer ? $cart->customer->email : '',
  //               'newsletter' => $cart->customer ? $cart->customer->newsletter : 0,
		// 		'same_address' => $this->sameAddress($cart),
		// 		'billing' =>  $cart->invoice_address ? $this->getAddress($cart->invoice_address) : '',
		// 		'shipping' =>  $cart->delivery_address ? $this->getAddress($cart->delivery_address) : ''    
		// 	],
		// 	'shippings' => $this->getShippingQuotes($cart, $this->getShippingZones($cart)),
		// 	'payments' => $this->getPaymentOptions($cart, session('store')->payments()->where('active', 1)->get()),
  //           'shipping' => $this->getShipping($cart),
  //           'tax' => $this->getTaxRate($this->getTaxZone($cart), $cart),
		// 	'shipping_tax' => $this->getShippingTaxRate($this->getTaxZone($cart), $cart),
		// 	'discount' => $this->discount($cart)
		// ];

        $cart_data = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];

        return [
            'id' => $cart->reference,
            'cart' => $cart_data,
            'billing_address' => $cart->invoice_address ? fractal()->item($cart->invoice_address)->transformWith(new AddressTransformer)->toArray()['data'] : '',
            'shipping_address' => $cart->delivery_address ? fractal()->item($cart->delivery_address)->transformWith(new AddressTransformer)->toArray()['data'] : '',
            'note' => '',
            'customer' => fractal()->item(auth()->user())->transformWith(new CustomerTransformer)->toArray()['data'],
            'subtotal' => $cart_data['original_total_price'],
            'tax' => [
                'name' => 'Tax',
                'amount' => $this->getTaxRate($this->getTaxZone($cart), $cart)
            ],
            'surcharge' => $this->getSurcharge($cart), 
            'grand_total' => $cart_data['total_price'] + $this->getTaxRate($this->getTaxZone($cart), $cart) + $this->getSurcharge($cart) + $this->getCarrier($cart, $cart_data)['rate'],
            'consignment' => [
                'options' => $this->getShippingQuotes($cart, $this->getShippingZones($cart)),
                'selected' => $this->getCarrier($cart, $cart_data)
            ]
        ];
	}

    protected function getSurcharge(Cart $cart)
    {
        $surcharge = 0;

        if($cart->carrier && $cart->carrier->shippingZone->cod) {

            $surcharge = $cart->carrier->shippingZone->cod->surcharge;

        }

        return (float) $surcharge;
    }

    protected function getCarrier(Cart $cart, $basket)
    {
        $carrier = [];

        if($cart->carrier) {

            $carrier['id'] = $cart->carrier->id;
            $carrier['image'] = '';
            $carrier['name'] = $cart->carrier->display_name;
            $carrier['rate'] = (float) $cart->carrier->rate($basket['total_price'], $basket['item_count'], $basket['total_weight']);

        } elseif(array_key_exists(0,$this->getShippingQuotes($cart, $this->getShippingZones($cart)))) {

            $carrier = $this->getShippingQuotes($cart, $this->getShippingZones($cart))[0];
        }  

        return $carrier;
    }

    protected function getShipping(Cart $cart)
    {
        $shipping = [];

        if($cart->carrier) {

            $shipping['name'] = $cart->carrier->display_name.' - '.$cart->carrier->rate($cart->total(), $cart->items->count(), $cart->weight());
            $shipping['rate'] = (float) $cart->carrier->rate($cart->total(), $cart->items->count(), $cart->weight());
            
        } else {
            $shipping['name'] = '';
            $shipping['rate'] = 0;
        }
        
        return $shipping;
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

	protected function getShippingQuotes(Cart $cart, Collection $zones)
    {
        $shippings = [];

        if(session('store')->setting->enable_store_pickup) {
            $shippings['store_pickup']['id'] = 0; 
            $shippings['store_pickup']['image'] = ''; 
            $shippings['store_pickup']['name'] = 'Store Pickup'; 
            $shippings['store_pickup']['rate'] = 0; 
        }

        if($zones->count()) {

            $cart = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];

            foreach($zones as $zone) {

            	$methods = $zone->shippingZone->shippingMethods()->where('status', 1)->get();

                foreach($methods as $key => $method) {
                	$shippings[$key]['id'] = $method->id;
                    $shippings[$key]['image'] = '';
                	$shippings[$key]['name'] = $method->display_name;
                	$shippings[$key]['rate'] = (float) $method->rate($cart['total_price'], $cart['item_count'], $cart['total_weight']);
                }
            }
          
            return array_values(array_sort($shippings, function ($value) {
                return $value['rate'];
            }));

        }

        return $shippings;
        
    }

     protected function getShippingZones(Cart $cart)
    {
        $zones = collect([]);
        $shipping_zones = session('store')->shippingZones()->where('status', 1)->get();

        if($cart->delivery_address) {
            if($cart->delivery_address->country_id != '' && $cart->delivery_address->state_id != '' && $cart->delivery_address->zip_code != '') 
            {
                foreach($shipping_zones as $zone) {
                    $location =  $zone->locations()->where('country_id', $cart->delivery_address->country_id)->where('state_id', $cart->delivery_address->state_id)->first();
                    if($location) {
                        $zip_codes = collect(explode(',', $location->zip_codes));
                        if($zip_codes->contains($cart->delivery_address->zip_code)) {
                            $zones->push($location);
                        }
                    } else {
                        $location = $zone->locations()->where('country_id', $cart->delivery_address->country_id)->first();

                        if($location) {
                            $zones->push($location);
                        }
                    }
                   
                }
                
            } else if($cart->delivery_address->country_id != '' && $cart->delivery_address->zip_code != '') {

                foreach($shipping_zones as $zone) {
                    $location = $zone->locations()->where('country_id', $cart->delivery_address->country_id)->first();
                    if($location) {
                        $zip_codes = collect(explode(',', $location->zip_codes));
                        if($zip_codes->contains($cart->delivery_address->zip_code)) {
                            $zones->push($location);
                        }
                    }
                }

            } 
        }
       
        return $zones;
       
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