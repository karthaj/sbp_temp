<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable = ['trans_amount','trans_currency','order_currency','order_amount','exchange_rate','payment_method','refund','transaction_id','card_number','card_brand','card_expiration','card_holder','created_at_tz','created_at'];

    public $timestamps = false;

    protected $dates = [
        'created_at_tz',
    ];

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function currency()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Currency');
    }

}
