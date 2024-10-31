<?php

namespace Shopbox\Http\Controllers\Zpanel\Dropdown;

use Illuminate\Http\Request;
use Shopbox\Transformers\Dropdown\FontTransformer;
use Shopbox\Models\Zpanel\GFont;
use Shopbox\Http\Controllers\Controller;

class FontController extends Controller
{

    public function index(Request $request)
    {   
    	
    	$fonts = '';
    	
    	if($request->q) {
    		$fonts = GFont::orWhere('name', 'like', '%'.$request->q.'%')->get();
    	} else {
    		$fonts = GFont::all();
    	}

    	return  fractal()
                ->collection($fonts)
                ->transformWith(new FontTransformer)
                ->toArray()['data'];
    }

    public function show(Request $request)
    {

        $font = GFont::where('value', $request->font_variation)->first();

        if(!$font) {
            abort('404');
        }

        return  fractal()
                ->item($font)
                ->transformWith(new FontTransformer)
                ->toArray()['data'];
    }

}