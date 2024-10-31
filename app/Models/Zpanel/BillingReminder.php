<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class BillingReminder extends Model
{
    public $timestamps = false;

    protected $fillable = ['ref', 'created_at'];

    public function billing()
    {
    	return $this->belongsTo(Billing::class);
    }
}
