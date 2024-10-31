<?php

namespace Modules\Order\Filters\Order;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class IDFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{
		$value = explode("-", $value);

		if(count($value) == 2) {
			return $builder->where('orders.store_id', $value[0])->where('orders.order_id', $value[1]);
		}	

		return $builder;
	}	

}