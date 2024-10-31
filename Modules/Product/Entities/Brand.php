<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use ForTenants;

    protected $fillable = ['name', 'slug', 'small_default', 'medium_default', 'large_default', 'meta_title', 'meta_description', 'meta_keywords'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function store()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
