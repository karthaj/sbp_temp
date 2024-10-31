<?php

namespace Modules\Blog\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

	use ForTenants;


    protected $fillable = ['featured_image', 'author', 'title', 'slug', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'active', 'blocked'];

    protected $dates = ['created_at_tz', 'updated_at_tz'];

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
