<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;
use Shopbox\Tenant\Traits\ForTenants;

class StorePlugin extends Model
{
	use ForTenants;

	public $timestamps = false;
	
    public function store()
    {
    	return $this->belongsTo(Store::class);
    }

    public function plugin()
    {
    	return $this->belongsTo(Plugin::class);
    }
}
