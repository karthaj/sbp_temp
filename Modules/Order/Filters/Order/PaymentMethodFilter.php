<?php

namespace Modules\Order\Filters\Order;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\FilterAbstract;

class PaymentMethodFilter extends FilterAbstract
{
	public function filter(Builder $builder, $value)
	{
		$value = $this->getPaymentMethod($value);
		
		if($value) {
			$builder->where('payment_plugin', $value);
		}
		
		return $builder;
	}

	protected function getPaymentMethod($value)
	{
		if ($value === 'all') {
			return;
		}

		foreach (session('store')->payments as $payment) {
			
			if($payment->alias === $value) {
				return $value;
				break;
			}
		}
	}

}