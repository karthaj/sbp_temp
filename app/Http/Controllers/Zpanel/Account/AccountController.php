<?php

namespace Shopbox\Http\Controllers\Zpanel\Account;

use Illuminate\Http\Request;
use Shopbox\Events\Account\UserCreated;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\Permission;
use Shopbox\Models\Zpanel\User;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Http\Requests\Account\StaffRegisterFormRequest;
use Shopbox\Http\Requests\Account\StaffUpdateFormRequest;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;


class AccountController extends Controller
{ 
    protected $imageManager;
    
    public function __construct(ImageManager $imageManager) {
        $this->imageManager = $imageManager;
        $this->middleware(['account.limts']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('zpanel.account.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    { 
        $data = Permission::where('status', 1)->whereNull('parent_id')->orderBy('sort_order', 'asc')->get();
        $permissions = collect();
        $locations = $request->tenant()->storeLocations;

        foreach($data as $value) {
            foreach($value->childPermissions as $permission) {
                if(auth()->user()->can($permission->name)) {
                    $permissions->push($permission);
                }
            }
        }

        if($permissions->count()) {
            $permissions = $permissions->groupBy(function ($item, $key) {
                return $item->parent->name;
            });
        }

        return view('zpanel.account.create', compact('permissions', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffRegisterFormRequest $request)
    {  
        $user = new User;
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->password = bcrypt(str_random('10'));
        $user->save();
       
        if(count($request->permissions)) {
            $user->givePermissionTo($request->permissions);
        }

        if(count($request->locations)) {
            $user->storeLocations()->attach($request->locations);
        }

        if(!$user->stores()->where('store_id', $request->tenant()->id)->count()) {
            $user->stores()->attach(session('store')->id);
        }

        event(new UserCreated($user));

        return redirect()->route('account.users')->withSuccess('Account created successfully. Instructions for activating their account has been emailed to the user.');
    }

    public function resendEmail(User $user)
    { 
        event(new UserCreated($user));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        $data = Permission::where('status', 1)->whereNull('parent_id')->orderBy('sort_order', 'asc')->get();
        $permissions = collect();
        $locations = $request->tenant()->storeLocations;

        foreach($data as $value) {
            foreach($value->childPermissions as $permission) {
                if(auth()->user()->can($permission->name)) {
                    $permissions->push($permission);
                }
            }
        }

        if($permissions->count()) {
            $permissions = $permissions->groupBy(function ($item, $key) {
                return $item->parent->name;
            });
        }
        return view('zpanel.account.edit', compact('user', 'permissions', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StaffUpdateFormRequest $request, User $user)
    {  
        $user->first_name = $request->firstname;
        $user->last_name = $request->lastname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        
        if(count($request->permissions)) {
            $user->updatePermissions($request->permissions);
        }

        $user->storeLocations()->detach();
        
        if(count($request->locations)) {
            $user->storeLocations()->attach($request->locations);
        }
                                  
        return redirect()->route('account.users')->withSuccess('Staff updated successfully!');
    
    }

}
