<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class TaxZone extends Model
{
	use ForTenants;
	
    protected $fillable = ['name', 'type', 'status'];

    public function store()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function taxRules()
    {
        return $this->hasMany(TaxRule::class);
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }
}
