<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
	public $timestamps = false;
	protected $fillable = ['company', 'address1', 'address2', 'country', 'state', 'city', 'postcode', 'phone'];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }
}
