<?php

namespace Shopbox\Transformers\Checkout;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Modules\Customer\Entities\Customer;
use Shopbox\Transformers\Checkout\AddressTransformer;


class CustomerTransformer extends TransformerAbstract
{
	public function transform($customer)
	{
		return [
			'id' => $customer ? $customer->id : 0,
			'guest' => $customer ? (bool) $customer->is_guest : true,
			'firstname' => $customer ? $customer->firstname : '',
			'lastname' => $customer ? $customer->lastname : '',
			'email' => $customer ? $customer->customerEmail : '',
			'store_credits' => $customer ? $customer->credits->count() ? (float) $customer->credits()->orderBy('id', 'desc')->first()->balance : 0 : 0,
			'avatar' => $customer ? $customer->avatar ? asset('profiles/'.$customer->id.'/'.$customer->avatar) : '' : '',
			'addresses' => $customer ? fractal()->collection($customer->addresses)->transformWith(new AddressTransformer)->toArray()['data'] : []
		];
	}
	
}