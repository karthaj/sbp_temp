<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class UnsellableStock extends Model
{
	use ForTenants;
	
    protected $fillable = ['quantity', 'type', 'state'];

    public function store () {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function stock ()
    {
        return $this->belongsTo(Stock::class);
    }
}
