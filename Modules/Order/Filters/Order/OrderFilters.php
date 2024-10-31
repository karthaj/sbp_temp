<?php  

namespace Modules\Order\Filters\Order;
use Shopbox\Filters\FiltersAbstract;
use Modules\Order\Filters\Order\StatusFilter;
use Modules\Order\Filters\Order\SourceFilter;
use Modules\Order\Filters\Order\PaymentStatusFilter;
use Modules\Order\Filters\Order\PaymentMethodFilter;
use Modules\Order\Filters\Order\IDFilter;
use Modules\Order\Filters\Order\DateFilter;

class OrderFilters extends FiltersAbstract
{
	
	protected $filters = [
		'status' => StatusFilter::class,
		'source' => SourceFilter::class,
		'payment_status' => PaymentStatusFilter::class,
		'payment_method' => PaymentMethodFilter::class,
		'order_no' => IDFilter::class,
		'date' => DateFilter::class
	];

}