<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{ 
    protected $fillable = ['name', 'status'];

    public function plans() {
        return $this->belongsToMany(Plan::class, 'plans_permissions');  
    } 
    
    public function childPermissions() {
        return $this->hasMany(Permission::class, 'parent_id')->where('status', 1);
    } 

    public function parent() {
        return $this->belongsTo(Permission::class, 'parent_id');
    }

    public function plugin() {
        return $this->belongsTo(Plugin::class);
    }
    
}
