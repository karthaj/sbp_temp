<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class StoreLocation extends Model
{
    use ForTenants;

    protected $fillable = ['name', 'address', 'city', 'phone', 'postcode', 'active', 'online_sales', 'store_locations'];

    public function store() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function country() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Country');
    }
     
    public function state() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\State');
    }

    public function stocks() 
    {
        return $this->hasMany(StoreStock::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_location_users')->withTimestamps();
    }

}
