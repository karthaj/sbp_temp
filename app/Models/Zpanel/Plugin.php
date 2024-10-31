<?php

namespace Shopbox\Models\Zpanel;

use Shopbox\Filters\Plugin\PluginFilters;
use Illuminate\Database\Eloquent\Builder;  
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{ 
    protected $fillable = ['plugin_name', 'slug', 'author', 'email', 'description', 'price', 'is_core', 'version', 'status'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new PluginFilters($request))->add($filters)->filter($builder);
    }

    public function countries()
    {
    	return $this->hasMany(PluginCountry::Class);
    }

    public function plans()
    {
        return $this->hasMany(PluginPlan::Class);
    }

    public function stores()
    {
    	return $this->belongsToMany(Store::class, 'store_plugins');
    }

    public function category() {
        return $this->belongsTo(PluginCategory::class, 'type');
    }

    public function payments() {
        return $this->hasMany(Plugin::class, 'parent_id');
    }
    
    public function plugin() {
        return $this->belongsTo(Plugin::class, 'parent_id');
    }

    public function currency()
    {
        return $this->hasOne(PluginCurrency::Class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::Class);
    }

}
