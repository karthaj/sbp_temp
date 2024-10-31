<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Http\Requests\Feature\FeatureFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Feature;

class FeatureController extends Controller
{ 
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {  
        $feature = Feature::all();
        return view('product::features.index', compact('feature'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('product::features.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(FeatureFormRequest $request)
    {
        $feature = new Feature;
        $feature->name = $request->name;
        $feature->sort_order = Feature::getHighestOrder() + 1;
        $feature->save();
        if(count($request->value)) {
            for ($i=0; $i < count($request->value); $i++) { 
                $feature->values()->create([
                    'value' => $request->value[$i]
                ]);   
            }
        }
        return redirect()->route('features.index')->withSuccess('Feature created successfully!');
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
    public function edit(Feature $feature)
    { 
        //$feature = Feature::find($id);

        return view('product::features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(FeatureFormRequest $request, Feature $feature)
    { 
        if(count($request->value))
        {
            $feature->name = $request->name;
            $feature->save();
            $this->deleteValues($feature);
            
            for ($i=0; $i < count($request->value); $i++) { 
                $feature->values()->create([
                    'value' => $request->value[$i]
                ]);   
            }
            
        } else {
            $this->deleteValues($feature);
        }

        return redirect()->route('features.edit', $feature)->withSuccess('Feature updated successfully!');
    }

    public function sortOrder(Request $request)
    {
        if(count($request->values)) {
            $features = Feature::where('store_id', auth()->user()->stores()->first()->id)->get();
            for ($i=0; $i < count($request->values); $i++) { 
                $value = Feature::find($request->values[$i]['id']);
                $value->update([
                    'sort_order' => $request->values[$i]['sort_order']
                ]);
            }
        }
        
    }

    public function deleteValues(Feature $feature)
    {
        foreach($feature->values as $value) {
            $value->delete();
        }
    }   
}
