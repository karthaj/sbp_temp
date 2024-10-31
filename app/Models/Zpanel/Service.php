<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $fillable = ['name', 'ends_at', 'recurring', 'state'];

    protected $dates = ['ends_at'];

    public function store()
    {
    	return $this->belongsTo(Store::class);
    }

    public function plugin()
    {
    	return $this->belongsTo(Plugin::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function theme()
    {
        return $this->belongsTo('Shopbox\Models\Front\Theme');
    }

}
