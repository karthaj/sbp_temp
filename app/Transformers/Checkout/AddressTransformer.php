<?php

namespace Shopbox\Transformers\Checkout;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Modules\Customer\Entities\Address;


class AddressTransformer extends TransformerAbstract
{
	public function transform(Address $address)
	{
		
		return [
			'id' => $address->id,
			'alias' => $address->alias,
			'firstname' => $address->firstname,
			'lastname' => $address->lastname,
			'address1' => $address->address,
			'address2' => $address->address2,
			'country' => $address->country->iso_code,
			// 'country_code' => $address->country->iso_code,
			'state' => $address->state ? $address->state->iso_code : '',
			// 'state_code' => $address->state ? $address->state->iso_code : '',
	        'city' => $address->city,
			'postcode' => $address->zip_code,
			'phone' => $address->phone
		];
	}
	
}