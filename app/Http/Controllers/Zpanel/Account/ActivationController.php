<?php

namespace Shopbox\Http\Controllers\Zpanel\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\ConfirmationToken;

class ActivationController extends Controller
{
	protected $redirectTo = '/merchant/login';

    public function __construct()
    {
        $this->middleware(['confirmation_token.expired:/merchant/login']);
    }

    protected function redirectPath()
    {
    	return $this->redirectTo;
    }

    public function index(ConfirmationToken $token, Request $request)
    {
        $store = $token->user->stores->first()->store_name;
        return view('account.passwords.create', compact('token', 'store'));
    }

    public function activate(ConfirmationToken $token, Request $request)
    {
    	$this->validate($request, [
    		'password' => 'required|min:6|confirmed',
    	]);

    	$token->user->update([
    		'active' => 1,
    		'password' => bcrypt($request->password)
    	]);

    	return redirect($this->redirectPath())->withSuccess('Thank you for verifying your account.');
    }
}
