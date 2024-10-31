<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class PluginCurrency extends Model
{
    public $timestamps = false;

    public function plugin()
    {
    	return $this->belongsTo(Plugin::class);
    }

    public function currency()
    {
    	return $this->belongsTo(Currency::class);
    }
}
