<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class ShippingZoneMethod extends Model
{

    protected $fillable = ['display_name', 'rate', 'eligible_type', 'is_free', 'need_range', 'status'];

    public function shippingZone() 
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function shippingMethod() 
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function deliveryRates() 
    {
        return $this->hasMany(DeliveryRate::class);
    }

    public function rate($total, $items, $weight)
    {
        $weight = $this->convertWeight($weight);

        if($this->is_free && $total >= $this->min_order) {
            return $this->rate;

        } else if($this->eligible_type === 0) {

            return $this->rate;

        } else if($this->eligible_type === 1) {

            return $this->rate * $items;

        } else if($this->need_range) {

            if($this->restriction_type) {

                $rate = $this->deliveryRates()->where('delimiter1', '<=', $weight)->where('delimiter2', '>=', $weight)->first();

                if($rate) {
                    return $rate->price;
                }

            } else {

                $rate = $this->deliveryRates()->where('delimiter1', '<=', $total)->where('delimiter2', '>=', $total)->first();

                if($rate) {
                    return $rate->price;
                }
            }
        }

        return 0;
    }

    protected function convertWeight($weight)
    {
        $store_weight_unit = session('store')->setting->weight->weight_code;
        $amount = 0;

        if($store_weight_unit === 'kg') {

            $amount = $weight / 1000;

        } else if($store_weight_unit === 'g') {
            
            $amount = $weight;

        } else if($store_weight_unit === 'lb') {

            $amount = $weight / 453.592;

        } else if($store_weight_unit === 'oz') {

            $amount = $weight / 28.35;

        } else if($store_weight_unit === 't') {

            $amount = $weight / 907185;

        } 

        return (float) $amount;
    }
}
