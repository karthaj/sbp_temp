<?php

namespace Modules\Order\Filters\Order;
use Illuminate\Database\Eloquent\Builder;
use Modules\Order\Entities\OrderState;
use Shopbox\Filters\FilterAbstract;

class StatusFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		$status = OrderState::where('slug', $value)->first();

		if($status === null) {
			return $builder;
		}
		
		
		return $builder->where('current_state', $status->id);
	}

}