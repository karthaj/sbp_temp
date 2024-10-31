<?php

namespace Modules\Customer\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Customer\Entities\Address;


class AddressCollectionTransformer extends TransformerAbstract
{
	public function transform(Address $address)
	{
		
		return [
			'id' => $address->id,
			'address' => $this->buildAddress($address),
			'default' => (bool) $address->default,
		];
	}

	protected function buildAddress(Address $address)
	{
		$data = $address->address;

		if($address->address2) {
			$data .= ', '.$address->address2;
		}

		if($address->city) {
			$data .= ', '.$address->city->city_name;

			if($address->country->contains_states && $address->state) {
				$data .= ' '.$address->state->iso_code;
			}
		}	

		if($address->zip_code) {
			$data .= ', '.$address->zip_code;
		}

		$data .= ', '.$address->country->name;

		return $data;
	}
	
}