<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\Country;

class CountryController extends Controller
{
    public function getStates(Request $request)
    {
        $countries = $request->countries;
        $option = '';
        for ($i=0; $i < count($countries); $i++) { 
            $country = Country::where('id',$countries[$i])->where('status', 1)->first();
            $option .= '<optgroup label="'.$country->name.'">';
            foreach($country->states as $state) {
                $option .= '<option value="'.$country->id.'-'.$state->id.'">'.$state->name.'</option>';
            }
            $option .= '</optgroup>';
        }
        
        return response()->json([
            'states' => $option
        ]);
    }
}
