<?php

namespace Shopbox\Models\Zpanel;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class StorePayment extends Model
{
	use ForTenants;
	
    protected $fillable = ['display_name', 'active', 'created_by', 'updated_by', 'browser', 'platform', 'ip_address'];

    public function store() 
    {
    	return $this->belongsTo(Store::class);
    }

    public function plugin() 
    {
    	return $this->belongsTo(Plugin::class);
    }

    public function payments() 
    {
        return $this->hasMany('Modules\ShopboxPay\Entities\Config', 'payment_id');
    }
}
