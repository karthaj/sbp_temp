<?php

namespace Shopbox\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Shopbox\Http\Requests\Auth\ActivateResendRequest;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\User;
use Shopbox\Events\Auth\UserRequestedActivationEmail;

class ActivationResendController extends Controller
{
    public function index() 
    {
    	return view('auth.activation.resend.index');
    }

    public function store(ActivateResendRequest $request)
    {
    	$user = User::where('email', $request->email)->first();

    	if ($user->hasNotActivated()) {

    		event(new UserRequestedActivationEmail($user));
            return redirect()->route('admin.login')->withSuccess('An activation email has been sent.');

    	} else {
            return redirect()->route('admin.login')->withSuccess('Account already activated.');
        }

    	
    }
}
