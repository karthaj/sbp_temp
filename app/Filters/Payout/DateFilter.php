<?php

namespace Shopbox\Filters\Payout;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class DateFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{
		$value = explode('|', $value);
		
		if(count($value === 2)) {
			return $builder->whereDate('created_at', '>=', $value[0])->whereDate('created_at', '<=', $value[1]);
		}

	}

}