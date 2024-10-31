<?php

namespace Shopbox\Http\Controllers;

use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\User;

class AvailabilityController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('domain')) {

            $status = unique_domain([], $request->domain);

            return response()->json(compact('status'));

        } else if($request->has('email')) {

            $status = !(bool) User::where('email', $request->email)->count();

            return response()->json(compact('status'));

        } else if($request->has('phone')) {

            $status = !(bool) User::where('phone', $request->phone)->count();

            return response()->json(compact('status'));

        }
        
        abort(404);
    }

}
