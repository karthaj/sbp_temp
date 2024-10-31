<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class TaxClass extends Model
{
	use ForTenants;

    protected $fillable = ['name'];

    public function store ()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
