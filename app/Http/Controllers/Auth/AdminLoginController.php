<?php

namespace Shopbox\Http\Controllers\Auth;

use Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Shopbox\Models\Zpanel\User;

class AdminLoginController extends Controller
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

    protected function guard()
	{
	    return Auth::guard('admin');
	}

    /**
     * Where to redirect users after login.
     *
     * @var string
    */
    protected $redirectTo = '/merchant/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        if($request->user) {
            if(User::where('email', $request->user)->where('active', 1)->count() === 0) {
                $request->session()->flash('success', 'Please check your email for an activation link');
            } else {
                $request->session()->flush();
            }
        }
        
        return view('auth.login');
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
                ?: redirect()->intended($this->redirectPath());
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

        if($user->stores->count() > 1) {
            if(session()->has('store')) {
                $this->redirectTo = '//'.session('store')->domain.'.'.config('domain.app_domain').'/merchant/dashboard';
            }
            else{
                $this->redirectTo = '/merchant/manage/store';
            }
        } else {
            $store = $user->stores->first();
            $this->redirectTo = '//'.$store->domain.'.'.config('domain.app_domain').'/merchant/dashboard';
        }

        if ($user->hasNotActivated()) {

            $this->guard()->logout();

            return back()->withError('Your account is not active.');
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

        $domain = getStoreDomain(session('store'));
        $request->session()->invalidate();

        if($request->hasCookie('storefront_digest')) {
            $storefront_digest = Cookie::forget('storefront_digest', '/', $domain);
            return redirect('//'.config('domain.app_domain').'/merchant/login')->cookie($storefront_digest);
        }

        return redirect('//'.config('domain.app_domain').'/merchant/login');
    }

}
