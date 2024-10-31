<?php

namespace Shopbox\Filters\Plugin;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class PriceFilter extends FilterAbstract
{
	
	public function filter(Builder $builder, $value)
	{

		$values = ['free', 'paid'];

		if(in_array($value, $values)) {

			if($value === 'paid') {

				return $builder->where('price', '>', 0);

			} else if($value === 'free') {

				return $builder->where('price', '===', 0);

			}

		}

		return $builder;
	}	

}