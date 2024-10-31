<?php

namespace  Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
	
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

   
    public function countries ()
    {
        return $this->belongsToMany(Country::class, 'discount_countries');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

}
