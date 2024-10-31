<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;
use Shopbox\Tenant\Traits\ForTenants;

class StoreCredit extends Model
{
	use ForTenants;

    protected $fillable = ['invoice_number'];
	
    public function store()
    {
    	return $this->belongsTo(Store::class);
    }

    public function customer()
    {
    	return $this->belongsTo('Modules\Customer\Entities\Customer');
    }
}
