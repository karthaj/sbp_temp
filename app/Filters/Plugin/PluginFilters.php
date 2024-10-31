<?php  

namespace Shopbox\Filters\Plugin;
use Shopbox\Filters\FiltersAbstract;
use Shopbox\Filters\Plugin\PriceFilter;
use Shopbox\Filters\Plugin\SortFilter;

class PluginFilters extends FiltersAbstract
{
	
	protected $filters = [
		'sort_by' => SortFilter::class,
		'price' => PriceFilter::class
	];

}