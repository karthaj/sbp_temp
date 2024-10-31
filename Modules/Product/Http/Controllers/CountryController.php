<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
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

    public function getCountryStates(Request $request)
    {
        $country = Country::where('id',$request->country)->where('status', 1)->first(); 
        $result = 0;             
        $elem = '<div class="col-sm-6 form-group"><label for="state">State</label><select id="state" class="full-width form-control" name="state">';
        if($country->states->count()) {
            foreach($country->states as $state) {
                $elem .= '<option value="'.$state->id.'">'.$state->name.'</option>';
            }  
            $result = 1;
        } 
        $elem .='</select></div>';
        return response()->json([
            'states' => $elem,
            'result' => $result
        ]);
    }

    public function checkZipCode(Request $request)
    {
        $country = new Country;
        $result = $country->checkZipCode($request->country_id,$request->zipcode);

        return response()->json([
            'result' => $result
        ]);
    }

    public function getCountryCities(Request $request)
    {
        $country = Country::where('id',$request->country)->where('status', 1)->first(); 
        $result = 0;        
        $elem = '<div class="col-sm-12 form-group"><label for="zip_code">zip / postal codes</label><select id="zip_code" class="full-width form-control" name="zip_code[]" multiple>';
        if($country->cities->count()) {
            foreach($country->cities as $city) {
                $elem .= '<option value="'.$city->zip_code.'">'.$city->city_name.' | '.$city->zip_code.'</option>';
            } 
            $result = 1; 
        } 
        $elem .='</select></div>';
        return response()->json([
            'cities' => $elem,
            'result' => $result
        ]);
    }
    
}
