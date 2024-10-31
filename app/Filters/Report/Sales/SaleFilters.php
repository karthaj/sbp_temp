<?php  

namespace Shopbox\Filters\Report\Sales;
use Shopbox\Filters\FiltersAbstract;
use Shopbox\Filters\Report\RangeFilter;
use Shopbox\Filters\Report\Sales\GroupFilter;

class SaleFilters extends FiltersAbstract
{
	
	protected $filters = [
		'for' => RangeFilter::class,
		'by' => GroupFilter::class
	];

}