<?php

namespace Shopbox\Http\Middleware;

use Closure;
use Carbon\Carbon;

class Analytics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->for || !$request->by) {
            return response()->json([
                'message' => 'Missing aruguments'
            ], 422);
        }

        $range = explode(',', $request->for);

        if(count($range) < 2) {
            return response()->json([
                'message' => 'Range contains invalid data'
            ], 422);
        }

        try {
            Carbon::parse($range[0]);
            Carbon::parse($range[1]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid date format'
            ], 422);
        }

        $periods = ['day', 'month', 'hour', 'day_of_week'];

        if(!in_array($request->by, $periods)) {
            return response()->json([
                'message' => 'Period contains invalid data'
            ], 422);
        }
        
        return $next($request);
    }
}
