<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class TaxRule extends Model
{
    use ForTenants;

    protected $fillable = ['zip_codes'];

    public function store() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function taxZone() 
    {
        return $this->belongsTo(TaxZone::class);
    }

    public function state() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\State');
    }

    public function country() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Country');
    }
}
