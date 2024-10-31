<?php

namespace Shopbox\Http\Controllers\Auth;

use Carbon\Carbon;
use Shopbox\Models\Front\Theme;
use Jenssegers\Agent\Agent;
use Modules\Customer\Entities\Customer;
use Shopbox\Events\Auth\CustomerSignedUp;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Shopbox\Models\Zpanel\Track;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    protected $agent;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest');
        $this->agent = new Agent();
    }

    public function showRegistrationForm(Request $request)
    {
    
        return view($request->viewPath.'.register');

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique_email:customers,email',
            'password' => 'required|string|min:6|confirmed'
        ], [
            'email.unique_email' => 'Email already taken.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Shopbox\User
     */
    protected function create(array $data)
    {
        return Customer::create([
            'firstname' => $data['first_name'],
            'lastname' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'active' => 0,
            'ip_address' => Track::getRealIpAddr(),
            'browser' => $this->agent->browser(),
            'platform' => $this->agent->platform()
        ]);

    }


    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();

        event(new CustomerSignedUp($user, getStoreUrl(session('store'))));

        return redirect($this->redirectPath())->withSuccess('Please check your email for an activation link.');
    }
}
