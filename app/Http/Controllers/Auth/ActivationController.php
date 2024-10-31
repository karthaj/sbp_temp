<?php

namespace Shopbox\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Shopbox\Mail\Onboard\WelcomeEmail;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\ConfirmationToken;

class ActivationController extends Controller
{

	protected $redirectTo = '/merchant/login';

    public function __construct()
    {
        $this->middleware(['confirmation_token.expired:/']);
    }

    public function activate(ConfirmationToken $token, Request $request)
    {
    	$token->user->update([
    		'active' => 1
    	]);

        $store = $token->user->stores->first();

        if(!$store) {
            abort('404');
        }

        $store->expiry_date = Carbon::now()->addDays(14);
        $store->save();

        Mail::to($token->user)->queue(new WelcomeEmail($store));

    	return redirect()->intended($this->redirectPath())->withSuccess('Thank you for verifying your account.');
    }

    protected function redirectPath()
    {
    	return $this->redirectTo;
    }
}
