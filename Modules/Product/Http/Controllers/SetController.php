<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\OptionSet;
use Modules\Product\Http\Requests\Attribute\Sets\OptionSetFormRequest;
use Modules\Product\Http\Requests\Attribute\Sets\OptionSetUpdateFormRequest;

class SetController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $attributes = Attribute::all();
        
        return view('product::attributes.sets.create', compact('attributes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(OptionSetFormRequest $request)
    {
        $option_set = new OptionSet;
        $option_set->name = $request->name;
        $option_set->save();
        $option_set->attributes()->syncWithoutDetaching($request->options);

        return redirect()->route('attributes.index')->withSuccess('The new option set has been created successfully.');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(OptionSet $option_set)
    {
        $attributes = Attribute::all();

        return view('product::attributes.sets.edit', compact('option_set', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(OptionSetFormRequest $request, OptionSet $option_set)
    {
        $option_set->name = $request->name;
        $option_set->save();
        $option_set->attributes()->detach($option_set->attributes->pluck('pivot.attribute_id')->toArray());
        $option_set->attributes()->attach($request->options);
        $option_set->save();

        return redirect()->route('attributes.index')->withSuccess('The new option set has been updated successfully.');
    }

}
