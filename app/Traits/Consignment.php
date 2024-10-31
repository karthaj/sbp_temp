<?php

namespace Shopbox\Traits;

use Illuminate\Support\Collection;
use Modules\Product\Entities\Cart;
use Shopbox\Transformers\Cart\CartTransformer;

trait Consignment
{
	
	protected function getShippingQuotes(Cart $cart, Collection $zones)
    {
        $shippings = [];

        if(session('store')->setting->enable_store_pickup) {
            $shippings['store_pickup']['id'] = 0; 
            $shippings['store_pickup']['image'] = ''; 
            $shippings['store_pickup']['name'] = session('store')->setting->store_pickup_display_name; 
            $shippings['store_pickup']['rate'] = (float) 0; 
            $shippings['store_pickup']['instructions'] = html_entity_decode(session('store')->setting->store_pickup_instructions); 
        }

        if($zones->count()) {

            $cart = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];

            foreach($zones as $zone) {

                $methods = $zone->shippingMethods()->where('status', 1)->get();

                foreach($methods as $method) {
                    if($cart['total_price'] >= $method->min_order) {
                        $shippings[$method->id]['id'] = $method->id;
                        $shippings[$method->id]['image'] = '';
                        $shippings[$method->id]['name'] = $method->display_name;
                        $shippings[$method->id]['rate'] = (float) $method->rate($cart['total_price'], $cart['item_count'], $cart['total_weight']);
                    }                	
                }
            }

        }

        return array_values(array_sort($shippings, function ($value) {
            return $value['rate'];
        }));
        
    }

    protected function getShippingZones(Cart $cart)
    {
        $zones = collect([]);
        $shipping_classes = collect([]);
        $shipping_class = 0;

        foreach($cart->items as $item) {
            if($item->product->shipping_class_id) {
                $shipping_classes->push($item->product->shipping_class_id);
            }   
        }

        if($shipping_classes->count()) {
             $shipping_class = head($shipping_classes->sort()->values()->mode());
        }      

        if($shipping_class) {

            $shipping_class = session('store')->shippingClasses()->where('id', $shipping_class)->first();

            if($shipping_class && $cart->delivery_address) {

                $zones = session('store')->shippingZones()->with(['locations'])->where('status', 1)
                        ->whereHas('locations', function($query) use ($cart) {
                            $query->whereRaw('FIND_IN_SET("'.$cart->delivery_address->zip_code.'", zip_codes)');
                        })
                        ->groupBy('shipping_zones.id')
                        ->get();

            }

        } else {

            if($cart->delivery_address) {

                $zones = session('store')->shippingZones()->with(['locations'])->where('status', 1)
                        ->whereHas('locations', function($query) use ($cart) {
                            $query->whereRaw('FIND_IN_SET("'.$cart->delivery_address->zip_code.'", zip_codes)');
                        })
                        ->groupBy('shipping_zones.id')
                        ->get();

            }
        }

        if($cart->delivery_address) {

            if(!$zones->count()) {

                // fallback state
                if($cart->delivery_address->state_id) {
                    $zones = session('store')->shippingZones()->with(['locations'])->where('status', 1)
                            ->whereHas('locations', function($query) use ($cart) {
                                $query->where('state_id', $cart->delivery_address->state_id);
                            })
                            ->groupBy('shipping_zones.id')
                            ->get();
                }
                

                // fallback country
                if(!$zones->count()) {
                    $zones = session('store')->shippingZones()->with(['locations'])
                            ->where('status', 1)
                            ->where('zone_type', 'country')
                            ->whereHas('locations', function($query) use ($cart) {
                                $query->where('country_id', $cart->delivery_address->country_id);
                            })
                            ->groupBy('shipping_zones.id')
                            ->get();
                }
            }

        }

        return $zones;
       
    }

}