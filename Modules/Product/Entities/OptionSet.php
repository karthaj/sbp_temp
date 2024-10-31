<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class OptionSet extends Model
{
	use ForTenants;

    protected $fillable = ['name', 'created_at_tz', 'updated_at_tz'];

    public function attributes()
    {
    	return $this->belongsToMany(Attribute::class, 'attribute_option_sets');
    }

    public function store () {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }
}
