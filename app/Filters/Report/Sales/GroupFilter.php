<?php

namespace Shopbox\Filters\Report\Sales;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class GroupFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{

		if($value === 'day') {

			return $builder->selectRaw('DATE_FORMAT(created_at, "%b %e") as date, total_paid as metrics');

		} else if($value === 'month') {

			return $builder->selectRaw('DATE_FORMAT(created_at, "%b") as date, total_paid as metrics');

		} else if($value === 'hour') {

			return $builder->selectRaw('DATE_FORMAT(created_at, "%H %p") as date, total_paid as metrics');
			
		} else if($value === 'day_of_week') {
			
			return $builder->selectRaw('DATE_FORMAT(created_at, "%a") as date, total_paid as metrics');

		}
	}	

}