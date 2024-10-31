<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $fillable = ['created_at_tz','updated_at_tz'];

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function state()
    {
    	return $this->belongsTo(OrderState::class, 'order_state_id');
    }

    public function user()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\User');
    }
}
