<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ShippingZoneLocation extends Model
{
    protected $fillable = ['zip_codes'];

    public function store() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function shippingZone() 
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function state() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\State');
    }

    public function country() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Country');
    }
}
