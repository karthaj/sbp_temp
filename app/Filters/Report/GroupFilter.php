<?php

namespace Shopbox\Filters\Report;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class GroupFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{

		if($value === 'day') {

			return $builder->selectRaw('DATE_FORMAT(created_at, "%b %e") as date, COUNT(created_at) as metrics');

		} else if($value === 'month') {

			return $builder->selectRaw('DATE_FORMAT(created_at, "%b") as date, COUNT(created_at) as metrics');

		} else if($value === 'hour') {

			return $builder->selectRaw('DATE_FORMAT(created_at, "%H %p") as date, COUNT(created_at) as metrics');
			
		} else if($value === 'day_of_week') {
			
			return $builder->selectRaw('DATE_FORMAT(created_at, "%a") as date, COUNT(created_at) as metrics');

		}
	}	

}