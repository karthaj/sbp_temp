<?php

namespace Shopbox\Transformers\StoreFront;

use League\Fractal\TransformerAbstract;
use Modules\Customer\Entities\Customer;


class CustomerTransformer extends TransformerAbstract
{
	public function transform(Customer $customer)
	{
		
		return [
			'firstname' => $customer->firstname,
			'lastname' => $customer->lastname,
			'email' => $customer->email,
			'wishlists' => $customer->wishlists()->where('store_id', session('store')->id)->pluck('product_id')
		];

	}
	
}