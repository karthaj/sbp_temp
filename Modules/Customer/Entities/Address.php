<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'firstname',
    	'lastname',
    	'address',
    	'address2',
    	'postcode',
        'city',
    	'province',
    	'company',
    	'phone',
    	'default'
    ];

    public function customer ()
    {
    	return $this->belongsTo(Customer::class);
    }

    public function country ()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Country');
    }

    public function state ()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\State');
    }

    public function carts ()
    {
        return $this->hasMany('Modules\Product\Entities\Cart');
    }
}
