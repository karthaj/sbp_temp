<?php

namespace Modules\CashOnDelivery\Entities;

use Illuminate\Database\Eloquent\Model;

class COD extends Model
{
    protected $fillable = ['surcharge', 'remark', 'status', 'created_by', 'updated_by', 'browser', 'ip_address'];

    protected $table = 'cash_on_deliveries';

    public function zone()
    {
    	return $this->belongsTo('Modules\Product\Entities\ShippingZone', 'zone_id');
    }

    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
