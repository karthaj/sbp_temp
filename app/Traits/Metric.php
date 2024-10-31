<?php

namespace Shopbox\Traits;

use Carbon\Carbon;

trait Metric
{
	
	public function metrics($metrics, $from, $to, $period, $metric)
	{
		$data = [];
    	$interval = 0;
    	$range_from = Carbon::parse($from);
    	$range_to = Carbon::parse($to);

    	if($period === 'day') {
    		$interval = $range_from->diffInDays($range_to);
    		$range_from->subDay();
    	} else if($period === 'month') {
    		$interval = $range_from->diffInMonths($range_to);
    		$range_from->subMonth();
    	} else if($period === 'hour') {
    		$interval = $range_from->diffInHours($range_to->addHours(23));
    		$range_from->subHour();
    	} else if($period === 'day_of_week') {
    		$interval = 6;
    		$range_from->subDays(2);
    	}

    	for ($i=0; $i <= $interval; $i++) { 
        	
        	if($period  === 'day') {
        		$date = $range_from->addDay()->format('M d');
        	} else if($period  === 'month') {
        		$date = $range_from->addMonth()->format('M');
        	} else if($period  === 'hour') {
        		$date = $range_from->addHour()->format('g A');
        	} else if($period  === 'day_of_week') {
        		$date = $range_from->addWeek()->addDay()->format('D');
        	}
   
        	$data[$i]['date'] = $date;
       
        	if($metrics->where('date', $date)->count()) {
        		$data[$i][$metric] = $metrics->where('date', $date)->first()->metrics;
        	} else {
        		$data[$i][$metric] = 0;
        	}	
        }

        return $data;

	}

}