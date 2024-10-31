<?php  

namespace Shopbox\Filters\Theme;
use Shopbox\Filters\FiltersAbstract;
use Shopbox\Filters\Theme\SortFilter;
use Shopbox\Filters\Theme\PriceFilter;
use Shopbox\Filters\Theme\IndustryFilter;

class ThemeFilters extends FiltersAbstract
{
	
	protected $filters = [
		'sort_by' => SortFilter::class,
		'industry' => IndustryFilter::class,
		'price' => PriceFilter::class
	];

}