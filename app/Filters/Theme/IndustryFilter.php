<?php

namespace Shopbox\Filters\Theme;
use Shopbox\Models\Front\Industry;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class IndustryFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{

		$values = Industry::where('status', 1)->pluck('slug')->toArray();

		if(is_array($value)) {

			$filters = array_where($value, function ($value, $key) use ($values) {
			    return in_array($value, $values);
			});

			if(count($filters) > 1) {

				foreach ($filters as $filter) {
		
					foreach ($filters as $filter) {
					
						$builder = $builder->whereHas('industries', function(Builder $builder) use ($filter) {

							$builder->orWhere('slug', $filter);

						});

					}
					
				}

				return $builder;
			
			}

			return $this->filterByIndustry($builder, $filters[0]);

		} else if(in_array($value, $values)) {

			return $this->filterByIndustry($builder, $value);

		}

		return $builder;
	}	

	protected function filterByIndustry (Builder $builder, $value) 
	{
		return $builder->whereHas('industries', function(Builder $builder) use ($value) {

			$builder->where('slug', $value);

		});
	}

}