<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Shopbox\Tenant\Traits\ForTenants;

class OrderReturn extends Model
{
	use ForTenants;

    protected $fillable = ['reason', 'note', 'refund_method'];

    public function getRouteKeyName()
    {
        return 'return_id';
    }

    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function customer()
    {
    	return $this->belongsTo('Modules\Customer\Entities\Customer');
    }

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function details()
    {
    	return $this->hasMany(OrderReturnDetail::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderReturnState::class, 'state');
    }
}
