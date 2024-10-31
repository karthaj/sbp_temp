<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{

    use ForTenants;

    protected $fillable = ['rate'];

    public function store() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function tax() 
    {
        return $this->belongsTo(Tax::class);
    }

    public function taxClass() 
    {
        return $this->belongsTo(TaxClass::class);
    }
}
