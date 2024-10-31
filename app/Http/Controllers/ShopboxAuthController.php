<?php

namespace Shopbox\Http\Controllers;

use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Configuration;

class ShopboxAuthController extends Controller
{

    public function refresh(Request $request)
    {
    	$store = $request->tenant();

    	if(!$store) {
    		return abort('500');
    	}

    	$store->client()->update([
    		'secret' => str_random(40)
    	]);

    	return response()->json([
    		'secret' => $store->client->secret,
    		'message' => 'Secret key updated successfully.',
    	]);
    }


}
