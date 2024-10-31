<?php

namespace Shopbox\Http\Controllers\Front\Auth;

use Illuminate\Http\Request;
use Shopbox\Http\Requests\Auth\Customer\PasswordStoreRequest;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\VerificationToken;

class PasswordController extends Controller
{
    public function update(VerificationToken $token, PasswordStoreRequest $request)
    {
    	if ($token->hasExpired()) {

            return redirect('/')->withError('Token expired.');

        }

        $customer = $token->customer;
        $customer->newsletter = $request->newsletter;
        $customer->password = bcrypt($request->password);
        $customer->active = 1;
        $customer->save();

        // need to log the customer.

        return redirect('/');
    }
}
