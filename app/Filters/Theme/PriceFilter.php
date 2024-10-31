<?php

namespace Shopbox\Filters\Theme;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class PriceFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{
		$builder = $builder->where('status', 1);
		$values = ['free', 'paid'];

		if(is_array($value)) {

			$filters = array_where($value, function ($value, $key) use ($values) {
			    return in_array($value, $values);
			});

			if(count($filters) > 1) {

				foreach ($filters as $filter) {
					

					if($filter === 'free') {

						$builder->orWhere('price', '===', 0);

					} else if($filter === 'paid') {

						$builder->orWhere('price', '>', 0);

					}

				}

				return $builder;
			
			}

			return $this->filterByPrice($builder, $filters[0]);

		} else if(in_array($value, $values)) {

			return $this->filterByPrice($builder, $value);

		}

		return $builder;
	}	

	protected function filterByPrice (Builder $builder, $value) 
	{
		if($value === 'paid') {

			return $builder->where('price', '>', 0);

		} else if($value === 'free') {

			return $builder->where('price', '===', 0);

		}
	}

}