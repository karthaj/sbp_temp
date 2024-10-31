<?php

namespace Modules\Order\Filters\Order;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class SourceFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{
		if($value === 'all') {
			return $builder;
		}

		return $builder->where('order_source', $value);
	}

}