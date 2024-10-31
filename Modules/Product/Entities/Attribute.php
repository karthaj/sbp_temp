<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{

	use ForTenants;

    protected $fillable = ['name', 'public_name', 'group_type', 'display_type', 'default_value', 'max_value', 'file_type', 'start_date', 'end_date'];

    public function options()
    {
        return $this->hasMany(Option::class)->orderBy('sort_order', 'asc'); 
    }

    public function store() {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function option_sets()
    {
    	return $this->belongsToMany(OptionSet::Class, 'attribute_option_sets');
    }

}
