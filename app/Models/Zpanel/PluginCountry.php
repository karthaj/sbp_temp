<?php

namespace Shopbox\Models\Zpanel;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class PluginCountry extends Model
{

	public $timestamps = false;

    public function country()
    {
    	return $this->belongsTo(Country::class);
    }

}
