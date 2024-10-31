<?php

namespace Shopbox\Filters\Theme;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class SortFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{
		$values = ['newest', 'featured'];

		if(in_array($value, $values)) {

			if($value === 'newest') {

				return $builder->orderBy('created_at', 'desc');

			}

			return $builder->where('featured', 1);

		}

		return $builder;
	}	

}