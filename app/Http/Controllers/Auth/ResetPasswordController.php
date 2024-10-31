<?php

namespace Shopbox\Http\Controllers\Auth;

use Shopbox\Models\Front\Theme;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Shopbox\Notifications\Customer\PasswordChanged;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;


    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view($request->viewPath.'.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );

    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse($response)
    {
        $this->guard()->user()->notify(new PasswordChanged());

        if ($this->guard()->user()->hasNotActivated()) {
            $this->redirectTo = '/login';
            $this->guard()->logout();
        }

        return redirect($this->redirectPath())
                            ->with('success', trans($response));
    }
}
