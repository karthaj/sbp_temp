<?php

namespace Shopbox\Permissions;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\Plan;
use Shopbox\Models\Zpanel\Permission;


trait HasPermissionsTrait
{ 

    public function permissions () {
        return $this->belongsToMany(Permission::class, 'users_permissions')->withTimestamps();
    }
    
    public function hasPlan (...$plans) {

        foreach($plans as $plan) {
            if($this->plans->contains('name',$plan)) {
                return true;
            }
        }
        
        return false;
    }

    public function hasPermissionTo($permission) {
        
        if($this->master) {
            return request()->tenant()->hasPermissionTo($permission); 
        } else {
            return $this->hasPermission($permission);
        }
        
        //return $this->hasPermissionThroughPlan($permission) || $this->hasPermission($permission);

    }

    protected function hasPermission ($permission) {

        return (bool) $this->permissions->where('name', $permission->name)->count();

    }
    

    public function givePermissionTo (...$permissions) {
        $permissions = $this->getAllPermissions(array_flatten($permissions));
        if($permissions === null) {
            return $this;
        } 
        $this->permissions()->saveMany($permissions);
        return $this;   
    }

    public function givePlanPermissionTo (Plan $plan, ...$permissions) {

        $permissions = $this->getAllPermissions(array_flatten($permissions));
        if($permissions === null) {
            return $this;
        } 
        $plan->permissions()->saveMany($permissions);
        return $this;   
    }

    public function withdrawPermissionTo(...$permissions) {
        $permissions = $this->getAllPermissions(array_flatten($permissions));
        $this->permissions()->detach($permissions);
        return $this;
    }

    public function withdrawPlanPermissionTo(Plan $plan, ...$permissions) {
        $permissions = $this->getAllPermissions(array_flatten($permissions));
        $plan->permissions()->detach($permissions);
        return $this;
    }

    public function updatePermissions (...$permissions) {
        $this->permissions()->detach();
        return $this->givePermissionTo($permissions);
    }
    
    public function updatePlanPermissions (Plan $plan, ...$permissions) { 
       $plan->permissions()->detach();
       return $this->givePlanPermissionTo($plan, $permissions);
    }

    protected function getAllPermissions (array $permissions) {
        return Permission::whereIn('id', $permissions)->get();
    } 


}