<?php

namespace Modules\Page\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

	use ForTenants;


    protected $fillable = ['type', 'title', 'slug', 'body', 'meta_title', 'meta_description', 'meta_keywords', 'active', 'enable_form', 
                            'map'];

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
