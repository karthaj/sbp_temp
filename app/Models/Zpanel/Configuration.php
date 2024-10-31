<?php

namespace Shopbox\Models\Zpanel;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;


class Configuration extends Model
{	
    protected $fillable = ['name', 'value'];

    public function store()
    {
    	return $this->belongsTo(Store::class);
    }
}
