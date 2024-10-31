<?php

namespace Modules\Order\Filters\Order;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class DateFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{
		$value = explode('|', $value);

		if(count($value === 2)) {
			return $builder->whereBetween('orders.created_at', $value);
		}

	}

}