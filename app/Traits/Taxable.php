<?php

namespace Shopbox\Traits;
use Modules\Product\Entities\Cart;
use Shopbox\Transformers\Cart\ProductTransformer;

trait Taxable
{
	protected function getShippingTaxRate($tax_rule, Cart $cart)
    {
       $shipping_tax = 0;
       if($tax_rule != '' && $cart->carrier) {
            if($tax_rule->taxZone->taxes->count()) {

                $taxes = $tax_rule->taxZone->taxes()->where('status', 1)->orderBy('priority')->get();
                $prev_priority = $taxes->first()->priority;

                foreach ($taxes as $tax) {

                    if($tax->priority === $prev_priority) {

                        if($cart->carrier->need_range) {

                            if($cart->carrier->restriction_type) {

                                if($cart->carrier->deliveryRates()->where('delimiter1', '<=', $cart->weight())->where('delimiter2', '>=', $cart->weight())->first()) {
                                    $shipping_tax += $cart->carrier->deliveryRates()->where('delimiter1', '<=', $cart->weight())->where('delimiter2', '>=', $cart->weight())->first()->price * $tax->rates()->where('tax_class_id', session('store')->taxOption->shipping_tax)->first()->rate;
                                }

                            } else {

                                if($cart->carrier->deliveryRates()->where('delimiter1', '<=', $cart->total())->where('delimiter2', '>=', $cart->total())->first()) {
                                    $shipping_tax += $cart->carrier->deliveryRates()->where('delimiter1', '<=', $cart->total())->where('delimiter2', '>=', $cart->total())->first()->price * $tax->rates()->where('tax_class_id', session('store')->taxOption->shipping_tax)->first()->rate;
                                }

                            }

                        } else {

                            $shipping_tax += $cart->carrier->rate * $tax->rates()->where('tax_class_id', session('store')->taxOption->shipping_tax)->first()->rate;

                        }
                    } else if($tax->priority > $prev_priority) {
                        $shipping_tax += ($cart->carrier->rate + $shipping_tax) * $tax->rates()->where('tax_class_id', session('store')->taxOption->shipping_tax)->first()->rate;
                        if($cart->carrier->need_range) {
                            if($cart->carrier->restriction_type) {
                                if($cart->carrier->deliveryRates()->where('delimiter1', '<=', $cart->weight())->where('delimiter2', '>=', $cart->weight())->first()) {
                                    $shipping_tax += ($cart->carrier->deliveryRates()->where('delimiter1', '<=', $cart->weight())->where('delimiter2', '>=', $cart->weight())->first()->price + $shipping_tax) * $tax->rates()->where('tax_class_id', session('store')->taxOption->shipping_tax)->first()->rate;
                                }
                            } else {
                                if($cart->carrier->deliveryRates()->where('delimiter1', '<=', $cart->total())->where('delimiter2', '>=', $cart->total())->first()) {
                                    $shipping_tax += ($cart->carrier->deliveryRates()->where('delimiter1', '<=', $cart->total())->where('delimiter2', '>=',$cart->total())->first()->price + $shipping_tax) * $tax->rates()->where('tax_class_id', session('store')->taxOption->shipping_tax)->first()->rate;
                                }
                            }
                        } else {
                            $shipping_tax += ($cart->carrier->rate + $shipping_tax) * $tax->rates()->where('tax_class_id', session('store')->taxOption->shipping_tax)->first()->rate;
                        }
                    } 
                   
                   $prev_priority = $tax->priority;
                }
            }
       }
       
       return $shipping_tax;
    }

	protected function getTaxRule(Cart $cart)
    {
        $tax_zones = session('store')->taxZones()->where('status', 1)->get();

        if($cart->delivery_address) {

        	if($cart->delivery_address->country_id != '' && $cart->delivery_address->state_id != '' && $cart->delivery_address->zip_code != '') 
	        {
	            foreach($tax_zones as $zone) {
	               
                    if($zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->where('state_id', $cart->delivery_address->state_id)->whereNotNull('zip_codes')->count()) {

                        $tax_rule = $zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->where('state_id', $cart->delivery_address->state_id)->whereNotNull('zip_codes')->first();

                        $zip_codes = collect(explode(',', $tax_rule->zip_codes));

                        if($zip_codes->contains($cart->delivery_address->zip_code)) {

                            return $tax_rule;

                        }


                    } 

                    if($zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->where('state_id', $cart->delivery_address->state_id)->count()) {

                        return $zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->where('state_id', $cart->delivery_address->state_id)->first();

                    } else if($zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->count()) {

                        return $zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->first();
                    }
       
	            }
	            
	        } else if($cart->delivery_address->country_id != '' && $cart->delivery_address->state_id != '') {
	    
	            foreach($tax_zones as $zone) {

                    if($zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->where('state_id', $cart->delivery_address->state_id)->count()) {

                        return $zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->where('state_id', $cart->delivery_address->state_id)->first();

                    } else if($zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->count()) {

                        return $zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->first();
                    }

	            }
	            
	        } else if($cart->delivery_address->country_id != '' && $cart->delivery_address->zip_code != '') {

	            foreach($tax_zones as $zone) {

                    if($zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->whereNotNull('zip_codes')->count()) {

                        $tax_rule = $zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->whereNotNull('zip_codes')->first();

                        $zip_codes = collect(explode(',', $tax_rule->zip_codes));

                        if($zip_codes->contains($cart->delivery_address->zip_code)) {
                             return $tax_rule;
                        }

                    } 

                    if($zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->count()) {

                        return $zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->first();
                    }   
                
	            }
            
	        } else if($cart->delivery_address->country_id != '')  {
	            foreach($tax_zones as $zone) {
	              
	                if($zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->count()) {

	                    return $zone->taxRules()->where('country_id', $cart->delivery_address->country_id)->first();
	                }
	            }
	        }
        }
    }

	protected function getTaxRate($tax_rule, Cart $cart)
    {

       $sale_tax = 0;
       if($tax_rule != '') {
            if($tax_rule->taxZone->taxes->count()) {
                $taxes = $tax_rule->taxZone->taxes()->where('status', 1)->orderBy('priority')->get();
                $prev_priority = $taxes->first()->priority;

                foreach ($taxes as $tax) {

                   foreach($cart->items as $item) {

                        $product = fractal()->item($item)->transformWith(new ProductTransformer)->toArray()['data']; 

                        if($item->product->tax_class_id) {
                            if($tax->priority === $prev_priority) {

                                $sale_tax += $product['line_price'] * $tax->rates()->where('tax_class_id', $item->product->tax_class_id)->first()->rate;

                            } else if($tax->priority > $prev_priority) {

                                $sale_tax += ($product['line_price'] + $sale_tax) * $tax->rates()->where('tax_class_id', $item->product->tax_class_id)->first()->rate;
                            } 
                        }

                   }

                   $prev_priority = $tax->priority;

                }
            }
       }

       return $sale_tax;
     
       // $shipping_tax = $this->getShippingTaxRate($this->getTaxRule($cart), $cart);

       // return $sale_tax += $shipping_tax;
    }

    protected function getTaxes($tax_rule, Cart $cart)
    {
        $data = [];
        $sale_tax = 0;

        if($tax_rule != '') {
            if($tax_rule->taxZone->taxes->count()) {
                $taxes = $tax_rule->taxZone->taxes()->where('status', 1)->orderBy('priority')->get();
                $prev_priority = $taxes->first()->priority;

                foreach ($taxes as $index => $tax) {
                   
                    foreach($cart->items as $item) {
                        $product = fractal()->item($item)->transformWith(new ProductTransformer)->toArray()['data']; 

                        if($item->product->tax_class_id) {
                            if($tax->priority === $prev_priority) {

                                $sale_tax += $product['line_price'] * $tax->rates()->where('tax_class_id', $item->product->tax_class_id)->first()->rate;

                            } else if($tax->priority > $prev_priority) {

                                $sale_tax += ($product['line_price'] + $sale_tax) * $tax->rates()->where('tax_class_id', $item->product->tax_class_id)->first()->rate;
                            } 
                        }
                    }

                    $prev_priority = $tax->priority;
                    if($sale_tax > 0) {
                        $data[$index]['name'] = $tax->name;
                        $data[$index]['amount'] = $sale_tax;
                    }
                    $sale_tax = 0;
                }
            }
        }
        return $data;
    }
}