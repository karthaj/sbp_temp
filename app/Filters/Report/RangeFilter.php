<?php

namespace Shopbox\Filters\Report;

use Carbon\Carbon;
use Shopbox\Filters\FilterAbstract;
use Illuminate\Database\Eloquent\Builder;

class RangeFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{

		$range = explode(',', $value);

		$range_from = Carbon::parse($range[0]);
		$range_to = Carbon::parse($range[1]);

		return $builder->whereBetween('created_at', [$range_from->toDateString(), $range_to->toDateString()]);

	}	

}