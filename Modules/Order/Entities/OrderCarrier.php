<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderCarrier extends Model
{
    protected $fillable = ['weight','shipping_cost_tax_excl','shipping_cost_tax_incl','tracking_number','created_at_tz','updated_at_tz'];

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function carrier()
    {
        return $this->belongsTo('Modules\Product\Entities\ShippingZoneMethod', 'carrier_id');
    }
}
