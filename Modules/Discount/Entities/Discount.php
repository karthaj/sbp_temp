<?php

namespace Modules\Discount\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
	use ForTenants;
	
    protected $fillable = [
    	'name',
    	'code',
    	'minimum_amount',
    	'reduction_percent',
    	'reduction_amount',
    	'quantity',
    	'quantity_per_user',
    	'entire_order',
    	'active',
    	'expires_at'
    ];

    protected $dates = ['expires_at'];

    public function store ()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function customer ()
    {
        return $this->belongsTo('Modules\Customer\Entities\Customer');
    }

    public function group ()
    {
        return $this->belongsTo('Modules\Customer\Entities\Group');
    }

    public function product ()
    {
        return $this->belongsTo('Modules\Product\Entities\Product');
    }

    public function category ()
    {
        return $this->belongsTo('Modules\Product\Entities\Category');
    }

    public function countries ()
    {
        return $this->belongsToMany('Shopbox\Models\Zpanel\Country', 'discount_countries');
    }

    public function carts()
    {
        return $this->hasMany('Modules\Product\Entities\CartDiscount');
    }

    public function invoices()
    {
        return $this->hasMany('Shopbox\Models\Zpanel\Invoice');
    }

}
