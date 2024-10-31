<?php

namespace Shopbox\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\VerificationToken;
use Modules\Product\Entities\Cart;

class VerificationController extends Controller
{

	protected $redirectTo = '/my-account';

    public function __construct()
    {
        $this->middleware(['verification_token.expired:/login']);
    }

    public function verify(VerificationToken $token, Request $request)
    {
    	$token->customer->update([
    		'active' => 1
    	]);

    	Auth::loginUsingId($token->customer->id);

        $cart = $cart = Cart::where('id', $request->cookie('cart'))->first();

        if($cart != '') {
            $cart->customer()->associate(auth()->user());
        }

    	return redirect()->intended($this->redirectPath());
    }

    protected function redirectPath()
    {
    	return $this->redirectTo;
    }
}
