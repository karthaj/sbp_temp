<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;
use Shopbox\Tenant\Traits\ForTenants;

class Summary extends Model
{
	use ForTenants;
	
    protected $table = 'order_summaries';

    protected $fillable = ['cus_order_id','currency','order_amount','order_type','order_status','created_at_tz','updated_at_tz'];

    public function store()
    {
    	return $this->belongsTo(Store::class);
    }

    public function order()
    {
    	return $this->belongsTo('Modules\Product\Entities\Order');
    }

    public function status()
    {
    	return $this->belongsTo('Modules\Product\Entities\OrderState');
    }
}
