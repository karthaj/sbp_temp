<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class BillingItem extends Model
{
 	protected $fillable = ['amount', 'ends_at'];

  	public function billing()
    {
    	return $this->belongsTo(Billing::class);
    }

    public function service()
    {
    	return $this->belongsTo(Service::class);
    }

}
