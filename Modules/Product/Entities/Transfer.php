<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
	use ForTenants;

    protected $fillable = ['reference', 'type', 'remarks', 'status', 'stock_request'];

    protected $dates = ['created_at_tz', 'updated_at_tz'];

    public function getRouteKeyName()
    {
        return 'reference';
    }

    public function store () {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function stocks () {
        return $this->hasMany(TransferStock::class);
    }

    public function store_location () {
        return $this->belongsTo(StoreLocation::class);
    }
}
