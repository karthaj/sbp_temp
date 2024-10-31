<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class BillingInvoice extends Model
{

    protected $fillable = ['number', 'amount', 'state'];

    public function store()
    {
    	return $this->belongsTo(Store::class);
    }

    public function billing()
    {
    	return $this->belongsTo(Billing::class);
    }
}
