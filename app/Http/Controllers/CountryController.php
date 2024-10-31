<?php

namespace Shopbox\Http\Controllers;

use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Transformers\CountryTransformer;

class CountryController extends Controller
{
    public function states(Country $country)
    {
        $states = getStates($country);

        return response()->json(compact('states'));
    }

    public function index()
    {
    	$countries = Country::with(['states', 'states.cities'])->where('status', 1)->orderBy('name', 'asc')->get();

    	return  fractal()
                ->collection($countries)
                ->transformWith(new CountryTransformer)
                ->toArray(); 
    }
}
