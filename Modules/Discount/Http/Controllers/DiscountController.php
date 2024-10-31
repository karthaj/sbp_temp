<?php

namespace Modules\Discount\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Discount\Http\Requests\Discount\DiscountFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Models\Zpanel\Country;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Category;
use Modules\Customer\Entities\Group;
use Modules\Customer\Entities\Customer;
use Modules\Discount\Entities\Discount;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('discount::discounts.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $countries = Country::where('status', 1)->get();
        $groups = Group::all();
        $customers =  Customer::whereHas('stores', function ($query) {
                            $query->where('store_id', session()->get('tenant'));
                        })->get();
        $categories = Category::where('status', 1)->get();
        $products = Product::where('active', 1)->where('state', 1)->get();

        return view('discount::discounts.create', compact('countries', 'groups', 'customers', 'categories', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(DiscountFormRequest $request)
    {
        $discount = new Discount;
        $discount->name = $request->discount_name;
        $discount->code = $request->discount_code;
        $discount->minimum_amount = $request->minimum_amount ? (float) str_replace(",","",$request->minimum_amount) : 0;
        $discount->quantity = $request->quantity;
        $discount->quantity_per_user = $request->quantity_per_customer ? $request->quantity_per_customer : 0;
        $discount->active = $request->status;
        $discount->created_at_tz = $discount->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $discount->updated_at_tz = $discount->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);

        if($request->discount_type === 'percentage') {
            $discount->reduction_percent = (float) str_replace(",","",$request->discount_value);
        } else if ($request->discount_type === 'fixed') {
            $discount->reduction_amount = (float) str_replace(",","",$request->discount_value);
        }

        if($request->customer_restriction === 'specific_group') {
            $discount->group()->associate($request->group);
        } else if($request->customer_restriction === 'specific_customer') {
            $discount->customer()->associate($request->customer);
        }

        if($request->discount_condition === 'specific_category') {
            $discount->category()->associate($request->category);
        } else if($request->discount_condition === 'specific_product') {
            $discount->product()->associate($request->product);
        } else if($request->discount_condition === 'entire_order') {
            $discount->entire_order = 1;
        }

        if($request->expiry_date) {
            $discount->expires_at = $request->expiry_date;
        }

        $discount->save();

        if(!empty($request->countries)) {
            $discount->countries()->syncWithoutDetaching($request->countries);
        }

        return redirect()->route('discounts.index')->withSuccess('Discount created successfully.');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('discount::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Discount $discount)
    {
     
        $countries = Country::where('status', 1)->get();
        $groups = Group::all();
        $customers =  Customer::whereHas('stores', function ($query) {
                            $query->where('store_id', session()->get('tenant'));
                        })->get();
        $categories = Category::where('status', 1)->get();
        $products = Product::where('active', 1)->where('state', 1)->get();

        return view('discount::discounts.edit', compact('discount', 'countries', 'customers', 'groups', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(DiscountFormRequest $request, Discount $discount)
    {
        $discount->name = $request->discount_name;
        $discount->code = $request->discount_code;
        $discount->minimum_amount = $request->minimum_amount ? (float) str_replace(",","",$request->minimum_amount) : 0;
        $discount->quantity = $request->quantity;
        $discount->quantity_per_user = $request->quantity_per_customer ? $request->quantity_per_customer : 0;
        $discount->active = $request->status;
        $discount->updated_at_tz = $discount->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);

        if($request->discount_type === 'percentage') {
            $discount->reduction_percent = (float) str_replace(",","",$request->discount_value);
        } else if ($request->discount_type === 'fixed') {
            $discount->reduction_amount = (float) str_replace(",","",$request->discount_value);
        }

        if($request->customer_restriction === 'specific_group') {
            $discount->group()->associate($request->group);
            $discount->customer()->dissociate();
        } else if($request->customer_restriction === 'specific_customer') {
            $discount->customer()->associate($request->customer);
            $discount->group()->dissociate();
        } else if($request->customer_restriction === 'everyone') {
            $discount->customer()->dissociate();
            $discount->group()->dissociate();
        }   

        if($request->discount_condition === 'specific_category') {
            $discount->category()->associate($request->category);
            $discount->product()->dissociate();
            $discount->entire_order = 0;
        } else if($request->discount_condition === 'specific_product') {
            $discount->product()->associate($request->product);
            $discount->category()->dissociate();
            $discount->entire_order = 0;
        } else if($request->discount_condition === 'entire_order') {
            $discount->entire_order = 1;
            $discount->product()->dissociate();
            $discount->category()->dissociate();
        }

        if($request->expiry_date) {
            $discount->expires_at = $request->expiry_date;
        }

        $discount->save();

        if(!empty($request->countries)) {
            //$this->detachCountries($discount, $discount->countries);
            $discount->countries()->syncWithoutDetaching($request->countries);
        }

        return redirect()->route('discounts.index')->withSuccess('Discount updated successfully.');
    }

    protected function detachCountries(Discount $discount, $countries)
    {
        foreach($countries as $country) {
            $discount->countries()->detach($country->id);
        }
    }
   
}
