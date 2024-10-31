<?php

namespace Modules\Menu\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	use ForTenants;
	
    protected $fillable = ['name', 'slug', 'active'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function store ()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function items ()
    {
        return $this->hasMany(Item::class)->orderBy('order', 'asc');
    }


}
