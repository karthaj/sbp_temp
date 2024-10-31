<?php

namespace Modules\Customer\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
	use ForTenants;

    protected $fillable = ['name', 'slug', 'discount'];

    public function store ()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function customers() 
    {
        return $this->belongsToMany(Customer::class, 'customer_groups')->withTimestamps();
    }

    public function discounts ()
    {
        return $this->hasMany(GroupReduction::class);
    }
}
