<?php

namespace Modules\Customer\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Customer\Entities\Address;


class AddressTransformer extends TransformerAbstract
{
	public function transform(Address $address)
	{
		
		return [
			'address_id' => $address->id,
			'first_name' => $address->firstname,
			'last_name' => $address->lastname,
			'address1' => $address->address,
			'address2' => $address->address2,
			'country' => $address->country_id,
			'state' => $address->state_id,
			'city' => $address->city->id,
			'postcode' => $address->zip_code,
			'phone' => $address->phone,
		];
	}	
}