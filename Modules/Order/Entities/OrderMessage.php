<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    protected $fillable = ['message'];

    public $timestamps = false;

    public function customer()
    {
    	return $this->belongsTo('Modules\Customer\Entities\Customer');
    }

    public function user()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\User');
    }

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function messages() 
    {
        return $this->hasMany(OrderMessage::class, 'message_id');
    }
    
    public function message() 
    {
        return $this->belongsTo(OrderMessage::class, 'message_id');
    }
}
