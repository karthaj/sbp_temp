<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use ForTenants;

    protected $fillable = ['name', 'priority', 'status'];

    public function store () {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function zone () {
        return $this->belongsTo(TaxZone::class, 'tax_zone_id');
    }

    public function rates () {
        return $this->hasMany(TaxRate::class);
    }
}
