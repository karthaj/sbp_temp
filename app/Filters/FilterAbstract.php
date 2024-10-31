<?php

namespace Shopbox\Filters;
use Illuminate\Database\Eloquent\Builder;

abstract class FilterAbstract
{
	
	abstract function filter(Builder $builder, $value);
}