<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class FeaturedProduct extends Model
{
	use ForTenants;
	
    protected $fillable = [];

    public function store()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
