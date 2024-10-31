<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class ShippingClass extends Model
{
    use ForTenants;

    protected $fillable = ['name', 'slug', 'status'];

    public function store() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
    
    public function shippingZones()
    {
    	return $this->belongsToMany(ShippingZone::class, 'shipping_class_zones');
    }

    public function getRouteKeyName()
	{
	    return 'slug';
	}
	
}
