<?php

namespace Shopbox\Http\Controllers\Zpanel\Account;

use Intervention\Image\ImageManager;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Http\Requests\Account\ProfileStoreRequest;

class ProfileController extends Controller
{
   protected $imageManager;

   public function __construct(ImageManager $imageManager)
   {
    	$this->imageManager = $imageManager;
   }

   public function index()
   {
   		return view('zpanel.account.profile.index');
   }

   public function store(ProfileStoreRequest $request)
   {
    
      $request->user()->first_name = $request->first_name;
      $request->user()->last_name = $request->last_name;
      $request->user()->email = $request->email;
      $request->user()->phone = $request->phone;
      $request->user()->save();

      if($request->password) {
         $request->user()->password = bcrypt($request->password);
      }

      $request->user()->save();

   		$avatar = $request->first_name.'_'.$request->last_name;

   		if($request->file('avatar') != null) { 
          $path = public_path('stores').'/'.$request->tenant()->domain.'/img/';

          unlinkFile($path.'/'.$request->user()->avatar);
            
           $processedImage = $this->imageManager->make($request->file('avatar')->getPathName())
               ->encode('jpg', 60)
               ->save($path.'/'.$avatar.'.'.$request->file('avatar')->getClientOriginalExtension());
           $request->user()->update([
           		'avatar' => $avatar.'.'.$request->file('avatar')->getClientOriginalExtension()
           ]);
      
        } else if($request->deletedAvatar != null) { 
            unlinkFile($path.'/'.$request->user()->avatar);
            $user->avatar = null;
        }	

   		return back()->withSuccess('Account updated successfully.');
   }
}
