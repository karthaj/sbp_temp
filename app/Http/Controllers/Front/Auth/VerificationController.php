<?php

namespace Shopbox\Http\Controllers\Front\Auth;

use Illuminate\Http\Request;
use Modules\Product\Entities\Cart;
use Illuminate\Support\Facades\Auth;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\VerificationToken;
use Shopbox\Http\Requests\Auth\Customer\VerificationFormRequest;

class VerificationController extends Controller
{
    protected $redirectTo = '/account';

    public function __construct()
    {
        $this->middleware(['verification_token.expired:/login']);
    }

    public function index(VerificationToken $token) 
    {
        return view('auth.verify', compact('token'));
    }

    public function update(VerificationToken $token, VerificationFormRequest $request)
    {
        $token->customer->update([
            'newsletter' => $request->newsletter,
            'active' => 1
        ]);

        Auth::loginUsingId($token->customer->id);

        $cart = $cart = Cart::where('reference', $request->cookie('cart'))->first();

        if($cart != '') {
            $cart->customer()->associate(auth()->user());
            $cart->checkout_session_data = '{"checkout-customer-step":{"step_is_reachable":true,"step_is_complete":true},"checkout-addresses-step":{"step_is_reachable":true,"step_is_complete":false,"use_same_address":true},"checkout-shipping-step":{"step_is_reachable":false,"step_is_complete":false},"checkout-payment-step":{"step_is_reachable":false,"step_is_complete":false}}';
            $cart->save();
        }

        return redirect()->intended($this->redirectTo);

    }
}
