<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class CartCarrier extends Model
{
    protected $fillable = ['name', 'type', 'shipping_cost', 'created_at_tz', 'updated_at_tz'];

    protected $dates = ['created_at_tz', 'updated_at_tz'];

    public function carrier()
    {
    	return $this->belongsTo(ShippingZoneMethod::class, 'carrier_id');
    }

    public function cart()
    {
    	return $this->belongsTo(Cart::class);
    }

}
