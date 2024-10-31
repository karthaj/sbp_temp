<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderReturnDetail extends Model
{
    protected $fillable = ['quantity'];

    public function orderReturn()
    {
    	return $this->belongsTo(OrderReturn::class);
    }

    public function orderDetail()
    {
    	return $this->belongsTo(OrderDetail::class);
    }
}
