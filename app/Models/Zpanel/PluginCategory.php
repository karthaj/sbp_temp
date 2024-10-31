<?php

namespace Shopbox;

use Illuminate\Database\Eloquent\Model;

class PluginCategory extends Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function children() {
        return $this->hasMany(PluginCategory::class, 'parent_id');
    }
    
    public function category() {
        return $this->belongsTo(PluginCategory::class, 'parent_id');
    }

    public function plugins()
    {
        return $this->hasMany('Shopbox\Models\Zpanel\Plugin');
    }
}
