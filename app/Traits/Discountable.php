<?php

namespace Shopbox\Traits;

use Carbon\Carbon;
use Modules\Product\Entities\Cart;
use Modules\Discount\Entities\Discount;
use Modules\Product\Entities\CartProduct;
use Shopbox\Transformers\Cart\CartTransformer;
use Modules\Product\Entities\CartDiscount;

trait Discountable
{
    public function addDiscount(Cart $cart, $discount_code)
    {
        $data = $this->validateDiscount($cart, $discount_code);

        if($data['status'] == 200) {
            $this->saveDiscount($cart, $discount_code);
        }

        return $data;
    }

    protected function validateDiscount(Cart $cart, $discount_code)
    {
        $cart_data = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];

        $data = [
            'status' => 200,
            'message' => 'Success'
        ];

        $discount = session('store')->discounts()->where('active', 1)->where('code', $discount_code)->first();

        if($discount) {

            if($discount->expires_at && $discount->expires_at->lessThan(Carbon::now())) {

                $data['status'] = 400;
                $data['message'] = 'Coupon code expired.';

                return $data;
            } 

            if($discount->countries->count()) {

                if($cart->delivery_address) {

                    if($discount->countries->contains('id', $cart->delivery_address->country_id)) {

                        if($cart->customer) {

                            if($this->checkCustomerEligibility($discount, $cart)) {

                                $data = $this->checkDiscountLimitations($discount, $cart_data);

                                if($data['status'] == 200) {

                                    $data = $this->discountApplicableForCartItems($discount, $cart, $cart_data);

                                }

                            } else {

                                $data['status'] = 400;
                                $data['message'] = 'Coupon code is invalid.';
                            }
                            
                        } else {

                            $data['status'] = 400;
                            $data['message'] = 'Please add your email address or login to your account to use this coupon.';
                        }


                    } else {

                        $data['status'] = 400;
                        $data['message'] = 'Coupon code not eligible to your country.';
                    }

                }  else {

                    $data['status'] = 400;
                    $data['message'] = 'Please add your shipping address to use this coupon.';

                }

            } else {

                if($cart->customer) {

                    if($this->checkCustomerEligibility($discount, $cart)) { 

                        $data = $this->checkDiscountLimitations($discount, $cart_data);

                        if($data['status'] == 200) {

                            $data = $this->discountApplicableForCartItems($discount, $cart, $cart_data);

                        }

                    } else {

                        $data['status'] = 400;
                        $data['message'] = 'Coupon code is invalid.';
                    }

                } else {

                    $data['status'] = 400;
                    $data['message'] = 'Please add your email address or login to your account to use this coupon.';
                }

            }

        } else {
            $data['status'] = 400;
            $data['message'] = 'Coupon code is invalid.';
        }

        return $data;

    }

    protected function discountApplicableForCartItems(Discount $discount, Cart $cart, $cart_data)
    {
        
        $data = [
            'status' => 400,
            'message' => $discount->code.' coupon code isn’t valid for the items in your cart'
        ];

        if($discount->category_id != null && $discount->product_id == null) {

            foreach($cart->items as $item) {
            
                if($item->product->categories()->where('category_id', $discount->category_id)->count()) {
                    
                    $data['status'] = 200;
                    $data['message'] = 'Success.';
                    break;
                } 
            }
     
        } else if($discount->category_id == null && $discount->product_id != null) {

            if($cart->items->contains('product_id', $discount->product_id)) {

                $data['status'] = 200;
                $data['message'] = 'Success.';
                break;
            } 

        } else if($discount->entire_order) {

            $data['status'] = 200;
            $data['message'] = 'Success.';
        }
       
        return $data;
    }

    protected function checkDiscountLimitations(Discount $discount, $cart)
    {
        $data = [
            'status' => 200,
            'message' => 'Success'
        ];

        if($discount->quantity == 0 && $discount->quantity_per_user == 0) {

            if(!($cart['total_price'] >= (float) $discount->minimum_amount)) {

                $data['status'] = 400;
                $data['message'] = 'Minimum order value should be '.session('store')->setting->currency->iso_code.' '.number_format($discount->minimum_amount,2);

            } 
        } else if($discount->quantity == 0 && $discount->quantity_per_user != 0) {

            $quantity_per_user = $this->getDiscountUsagePerCustomer($discount, $cart['email']);

            if(!$quantity_per_user < $discount->quantity_per_user) {

                $data['status'] = 400;
                $data['message'] = 'Coupon code expired.';
            }

            if(!($cart['total_price'] >= (float) $discount->minimum_amount)) {

                $data['status'] = 400;
                $data['message'] = 'Minimum order value should be '.session('store')->setting->currency->iso_code.' '.number_format($discount->minimum_amount,2);

            } 

        } else if($discount->quantity != 0 && $discount->quantity_per_user == 0) {
                       
            $quantity = $this->getDiscountUsage($discount->carts);

            if(!$quantity < $discount->quantity) {

                $data['status'] = 400;
                $data['message'] = 'Coupon code expired.';
            }

            if(!($cart['total_price'] >= (float) $discount->minimum_amount)) {

                $data['status'] = 400;
                $data['message'] = 'Minimum order value should be '.session('store')->setting->currency->iso_code.' '.number_format($discount->minimum_amount,2);

            } 

        } else if($discount->quantity != 0 && $discount->quantity_per_user != 0) {

            $quantity = $this->getDiscountUsage($discount->carts);
            $quantity_per_user = $this->getDiscountUsagePerCustomer($discount, $cart['email']);

            if(!$quantity < $discount->quantity && !$quantity_per_user < $discount->quantity_per_user) {

                $data['status'] = 400;
                $data['message'] = 'Coupon code expired.';
            }

            if(!($cart['total_price'] >= (float) $discount->minimum_amount)) {

                $data['status'] = 400;
                $data['message'] = 'Minimum order value should be '.session('store')->setting->currency->iso_code.' '.number_format($discount->minimum_amount,2);

            } 

        }

        return $data;
    }


    protected function saveDiscount(Cart $cart, $discount_code)
    {
    	if(!$cart->discount) {
    		$cart_discount = new CartDiscount;
	        $cart_discount->cart()->associate($cart);
	        $cart_discount->discount()->associate(session('store')->discounts()->where('active', 1)->where('code', $discount_code)->first());
	        $cart_discount->save();
    	}

    }


    // protected function calculateDiscount(Discount $discount, $total)
    // {
    //     $discount_amount = 0;

    //     if($discount->reduction_percent != 0 && $discount->reduction_amount == 0) {
    //         $discount_amount +=  $total / 100 * $discount->reduction_percent;
    //     } else if($discount->reduction_amount != 0 && $discount->reduction_percent == 0) {
    //         $discount_amount += $total - $discount->reduction_amount;
    //     }

    //     return $discount_amount;

    // }   

    protected function checkCustomerEligibility(Discount $discount, Cart $cart)
    {
        if($discount->group_id != null && $discount->customer_id == null) {
            if($cart->customer->group) {
                if($cart->customer->group->id == $discount->group_id) {
                    return true;
                } 

                return false;
            } else {
                return false;
            }
        } else if($discount->group_id == null && $discount->customer_id != null) {
            if($cart->customer->id == $discount->customer_id) {
                return true;
            }

            return false;
        } 

        return true;
    }

    protected function getDiscountUsage($carts) 
    {
        $qty = 0;

    	if($carts->count()) {
    		foreach ($carts as $discount) {
    			if($discount->cart->order) {
    				$qty += 1;
    			} 
    		}
    	}

    	return $qty;
    }

    protected function getDiscountUsagePerCustomer(Discount $discount, $email)
    {
        return $discount->carts()->with(['customer' => function ($query) use($email) {
            $query->where('email', $email);
        }])->count();

    }

}