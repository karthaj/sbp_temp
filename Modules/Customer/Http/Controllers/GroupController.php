<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Customer\Http\Requests\Customer\Group\GroupFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Group;
use Modules\Customer\Entities\GroupReduction;
use Modules\Product\Entities\Category;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('customer::customers.groups.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $categories = Category::where('status', 1)->get();
        return view('customer::customers.groups.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(GroupFormRequest $request)
    {   
        $group = new Group;
        $group->name = $request->group_name;
        $group->slug = str_slug($request->group_name, '-');

        if($request->group_discount) {
            $group->discount = $request->group_discount;
        }

        $group->save();

        if(!empty($request->category_discount)) {

            $discounts = array_values($request->category_discount);

            foreach($discounts as $discount) {

                $reduction = new GroupReduction;
                $reduction->group()->associate($group);
                $reduction->category()->associate($discount['category']);
                $reduction->discount = $discount['reduction'];
                $reduction->save();

            }

        } 

        if(!empty($request->customers)) {

            foreach($request->customers as $customer) {

                if(session('store')->customers->contains('id', $customer)) {

                    $group->customers()->sync([$customer => ['store_id' => session('store')->id, 'created_at_tz' => $group->freshTimestamp()->timezone(session('store')->setting->timezone->timezone)]]);
                }

            }
            
        }

        return redirect()->route('customers.groups.index')->withSuccess('Customer group created successfully!');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('customer::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Group $group)
    {
        $categories = Category::where('status', 1)->get();
        return view('customer::customers.groups.edit', compact('group','categories'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(GroupFormRequest $request, Group $group)
    {
        $group->name = $request->group_name;

        if($request->group_discount) {
            $group->discount = $request->group_discount;
        }

        $group->save();       

        if(!empty($request->category_discount) && !empty($group->discounts)) {

            $this->deleteExistingDiscounts($group);
            $discounts = array_values($request->category_discount);

            foreach($discounts as $discount) {

                $reduction = new GroupReduction;
                $reduction->group()->associate($group);
                $reduction->category()->associate($discount['category']);
                $reduction->discount = $discount['reduction'];
                $reduction->save();

            }
                       
        } 

        if(!empty($request->customers)) {

            foreach($request->customers as $customer) {

                if(session('store')->customers->contains('id', $customer)) {

                    $group->customers()->sync([$customer => ['store_id' => session('store')->id, 'created_at_tz' => $group->freshTimestamp()->timezone(session('store')->setting->timezone->timezone)]]);
                }

            }

        } else {
            $group->customers()->detach();
        }

        return redirect()->route('customers.groups.index')->withSuccess('Customer group updated successfully!');   
    }

    protected function deleteExistingDiscounts(Group $group)
    {
        foreach($group->discounts as $discount) {
            $discount->delete();
        }
    }

}
