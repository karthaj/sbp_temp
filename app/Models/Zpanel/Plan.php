<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{ 
    protected $fillable = ['name', 'monthly', 'yearly', 'quarterly', 'half_monthly'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function permissions() {
        return $this->belongsToMany(Permission::class, 'plans_permissions')->withTimestamps();  
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
