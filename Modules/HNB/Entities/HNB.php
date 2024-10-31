<?php

namespace Modules\HNB\Entities;

use Illuminate\Database\Eloquent\Model;

class HNB extends Model
{
    protected $table = 'hnb_transactions';

    protected $fillable = ['merchant_id', 'auth_code', 'order_id', 'reference_no', 'last4', 'transaction_stain', 'amount', 'refund', 'eci_indicator', 'state', 'result'];

    public function order()
    {
    	return $this->belongsTo('Modules\Order\Entities\Order');
    }

    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
