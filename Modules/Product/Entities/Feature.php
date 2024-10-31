<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use ForTenants;
    
    protected $fillable = ['name', 'sort_order'];

    public function values()
    {
        return $this->hasMany(Value::class);
    }

    public function store()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }


    public static function getHighestOrder()
    {  
        return (int) Feature::where('store_id', auth()->user()->stores()->first()->id)->count();
    }
}
