<?php

namespace Shopbox\Http\Controllers\Country;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\Country;

class CountryController extends Controller
{
    public function getStatesByCountry(Country $country)
    {
        $states = getStates($country);
         
        $elem = '<option value="">None</option>';

        if($states->count()) {

            foreach($states as $state) {
                $elem .= '<option value="'.$state->id.'">'.$state->name.'</option>';
            }  
        } 

        return response()->json([
            'states' => $elem,
        ]);
    }
}
