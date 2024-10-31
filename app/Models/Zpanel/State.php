<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public function shippingZone() 
    {
        return $this->hasOne('Modules\Product\Entities\ShippingZoneLocation');
    }

    public function country() 
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::Class);
    }
}
