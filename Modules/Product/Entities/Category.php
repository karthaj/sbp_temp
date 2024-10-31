<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use ForTenants;

    protected $fillable = ['parent_id', 'name', 'description', 'slug', 'cover_image', 'thumb_image', 'meta_title', 'meta_description',                           'meta_keywords', 'sort_order', 'status'];
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function store () {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
    
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_categories');
    }
}
