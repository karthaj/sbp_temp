<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\ShippingZoneMethod;

class ShippingZoneMethodTransformer extends TransformerAbstract
{

	public function transform(ShippingZoneMethod $shipping)
	{
		
		return [
			'email' => $shipping->email,
			'display_name' => $shipping->display_name,
			'charge_by' => $shipping->restriction_type,
			'ranges' => $this->getDeliveryRates($shipping)
		];
	}


	protected function getDeliveryRates (ShippingZoneMethod $shipping) {

		$ranges = [];

		if($shipping->deliveryRates->count()) {
			foreach ($shipping->deliveryRates as $key => $rate) {
				$ranges[$key]['from'] = $rate->delimiter1;
				$ranges[$key]['to'] = $rate->delimiter2;
				$ranges[$key]['cost'] = $rate->price;
			}
		}

		return $ranges;

	}
	
}