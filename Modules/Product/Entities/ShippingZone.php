<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use ForTenants;

    protected $fillable = ['zone_name','zone_type','status'];

    public function getRouteKeyName()
    {
        return 'alias';
    }

    public function cod()
    {
        return $this->hasone('Modules\CashOnDelivery\Entities\COD', 'zone_id');
    }

    public function store() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
    
    public function locations()
    {
        return $this->hasMany(ShippingZoneLocation::class);
    }

    public function country() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Country');
    }

    public function state() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\State');
    }

    public function shippingMethods()
    {
        return $this->hasMany(ShippingZoneMethod::class);
    }

    public function shippingClasses()
    {
        return $this->belongsToMany(ShippingClass::class, 'shipping_class_zones');
    }
}
