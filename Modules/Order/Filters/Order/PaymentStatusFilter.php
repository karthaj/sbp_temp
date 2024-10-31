<?php

namespace Modules\Order\Filters\Order;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class PaymentStatusFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		
		if($value === 'received') {
			return $builder->where('total_real_paid', '>', 0);
		} else if($value === 'pending') {
			return $builder->where('total_real_paid', 0);
		} 
		
		return $builder;
	}

}