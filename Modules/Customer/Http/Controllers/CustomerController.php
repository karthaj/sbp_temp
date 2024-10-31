<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Customer\Events\Auth\CustomerRegistered;
use Modules\Customer\Http\Requests\Customer\CustomerFormRequest;
use Modules\Customer\Http\Requests\Customer\CustomerEditFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Models\Zpanel\Country;
use Modules\Customer\Entities\Group;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Entities\Address;
use Jenssegers\Agent\Agent;

class CustomerController extends Controller
{

    protected $agent;

    public function __construct()     
    {
        $this->agent = new Agent();
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('customer::customers.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $groups = Group::all();
        $countries = Country::where('status', 1)->get();
        return view('customer::customers.create', compact('groups', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CustomerFormRequest $request)
    {
        $customer = new Customer;
        $customer->firstname = $request->firstname;
        $customer->lastname = $request->lastname;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        if(!empty($request->group)) {
            $customer->group()->associate($request->group);
        }
        $customer->ip_address = getRealIpAddress();
        $customer->browser = $this->agent->browser();
        $customer->platform = $this->agent->platform();
        $customer->save();

        // $this->storePrimaryAddress($customer, $request);

        $request->tenant()->customers()->attach($customer->id, ['created_at_tz' => $request->tenant()->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone)]);

        event(new CustomerRegistered($customer));
        
        return redirect()->route('customers.index')->withSuccess('Customer created successfully!');
    }

    protected function storePrimaryAddress(Customer $customer, $request, Address $address = null)
    {
        $address = $address ? $address : new Address;
        $address->customer()->associate($customer->id);
        $address->country()->associate($request->country);
        if(!empty($request->state)) {
            $address->state()->associate($request->state);
        }  
        $address->firstname = $request->firstname;
        $address->lastname = $request->lastname;
        $address->company = $request->address_company;
        $address->phone = $request->phone;
        $address->address = $request->address1;
        if(!empty($request->address2)) {
            $address->address2 = $request->address2;
        }
        $address->city = $request->city;
        $address->zip_code = $request->postcode;
        $address->default = 1;
        $address->save();
    }


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Customer $customer)
    {
        $groups = Group::all();
        $countries = Country::where('status', 1)->get();

        return view('customer::customers.edit', compact('customer', 'groups', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(CustomerFormRequest $request, Customer $customer)
    {
        $customer->firstname = $request->firstname;
        $customer->lastname = $request->lastname;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        if(!empty($request->group)) {
            $customer->group()->associate($request->group);
        }
        $customer->ip_address = getRealIpAddress();
        $customer->browser = $this->agent->browser();
        $customer->platform = $this->agent->platform();
        $customer->save();

        // $this->storePrimaryAddress($customer, $customer->addresses->where('default', 1)->first(), $request);

        return redirect()->route('customers.index')->withSuccess('Customer updated successfully!');
    }

    public function getStates(Request $request)
    {
        $country = Country::where('id',$request->country)->where('status', 1)->first(); 
        $result = 0;      
        $elem = '';
        if($country->states->count()) {
            $elem .= '<label for="state">State/Province</label>';
            $elem .= '<select id="state" class="full-width form-control" name="state" required>';
            foreach($country->states as $state) {
                $elem .= '<option value="'.$state->id.'">'.$state->name.'</option>';
            }  
            $elem .='</select>';

            $result = 1;
           
        } else {
            $elem .= '<label for="province">State/Province</label>';
            $elem .= '<input type="text" name="province" id="province" class="form-control" disabled>';
        }

        return response()->json([
            'states' => $elem,
            'result' => $result
        ]);
    }

  
}
