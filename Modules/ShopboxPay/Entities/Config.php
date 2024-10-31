<?php

namespace Modules\ShopboxPay\Entities;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = ['display_name','tdr_rate','active','created_by','updated_by','browser','platform','ip_address','created_at_tz','updated_at_tz'];

    protected $table = 'shopboxpay_configs';

    public function payment()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\StorePayment', 'payment_id');
    }

    public function plugin()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Plugin');
    }
}
