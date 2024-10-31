<?php

namespace Shopbox\Http\Controllers\Auth;

use Shopbox\Models\Front\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Modules\Product\Entities\Cart;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/account';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');

    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {
      
        return view($request->viewPath.'.login');

    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            ['email' => $request->email, 'password' => $request->password, 'is_guest' => 0], $request->has('remember')
        );
    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->hasNotActivated()) {

            $this->guard()->logout();

            return back()->withError('Your account is not active.');
        }

        // $user->stores()->syncWithoutDetaching(session('store')->id, ['created_at_tz' => $user->freshTimestamp()->timezone(session('store')->setting->timezone->timezone)]);

        $cart = Cart::where('reference', $request->cookie('cart'))->first();

        if($cart) {
            $cart->customer()->associate(auth()->user());
            $cart->checkout_session_data = '{"checkout-customer-step":{"step_is_reachable":true,"step_is_complete":true},"checkout-addresses-step":{"step_is_reachable":true,"step_is_complete":false,"use_same_address":true},"checkout-shipping-step":{"step_is_reachable":false,"step_is_complete":false},"checkout-payment-step":{"step_is_reachable":false,"step_is_complete":false}}';
            $cart->save();
        }
    }

    /**
    * Log the user out of the application.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $store = session('store');  
        $request->session()->invalidate();

        $cart = Cart::where('reference', $request->cookie('cart'))->first();

        if($cart) {
            $cart->customer()->dissociate();
            $cart->checkout_session_data = '{"checkout-customer-step":{"step_is_reachable":true,"step_is_complete":false},"checkout-addresses-step":{"step_is_reachable":false,"step_is_complete":false,"use_same_address":true},"checkout-shipping-step":{"step_is_reachable":false,"step_is_complete":false},"checkout-payment-step":{"step_is_reachable":false,"step_is_complete":false}}';
            $cart->save();

            if($cart->discounts->count()) {
                $cart->discounts->detach();
            }
        }

        if ($request->has('redirect_url')) {
            
            return redirect($request->redirect_url);
        }

        return redirect(getStoreUrl($store));
    }
}
