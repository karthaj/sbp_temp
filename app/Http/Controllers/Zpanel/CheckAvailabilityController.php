<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\User;
use Shopbox\Models\Zpanel\Role;

class CheckAvailabilityController extends Controller
{
    public function check_email (Request $request) { 
        if($request->user == null ) {
            $result = User::where('email',strtolower($request->email))->count();  
        } else {
            $result = User::where('email',strtolower($request->email))->where('id', '<>', $request->user)->count();
        }
        
        return response()->json([
            'data' => $result
        ],200);
    }
    
    public function check_role (Request $request) { 
        if($request->user == null) {
            $result = Role::where('store_id', null)->where('name',strtolower($request->role))->count();
        }  else { 
            $user = User::findOrFail($request->user);
            $role = $user->roles->where('store_id', null)->where('name',strtolower($request->role))->count();
            
            if($role == 1) {
                $result = 0;
            } else { 
                $role = $request->role;
                if($user->has('roles')->get()->count()) {
                    $role = $user->roles->first()->name;
                }
                 
                $result = Role::where('store_id', null)->where('name', '<>', strtolower($role))->count();
            }
        }
        
        return response()->json([
            'data' => $result
        ],200);
    }
}
