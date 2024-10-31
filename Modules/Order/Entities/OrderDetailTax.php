<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderDetailTax extends Model
{
    protected $fillable = ['rate', 'unit_amount','total_amount'];

    public $timestamps = false;

    public function order()
    {
    	return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    public function tax()
    {
    	return $this->belongsTo('Modules\Product\Entities\Tax');
    }

    
}
