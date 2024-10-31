<?php  

namespace Shopbox\Filters\Report;
use Shopbox\Filters\FiltersAbstract;
use Shopbox\Filters\Report\RangeFilter;
use Shopbox\Filters\Report\GroupFilter;

class ReportFilters extends FiltersAbstract
{
	
	protected $filters = [
		'for' => RangeFilter::class,
		'by' => GroupFilter::class
	];

}