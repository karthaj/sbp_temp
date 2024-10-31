<?php  

namespace Shopbox\Filters\Payout;
use Shopbox\Filters\FiltersAbstract;
use Shopbox\Filters\Payout\DateFilter;

class PayoutFilters extends FiltersAbstract
{
	protected $filters = [
		'date' => DateFilter::class
	];

}