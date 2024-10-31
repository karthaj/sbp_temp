<?php

namespace Shopbox\Transformers;

use League\Fractal\TransformerAbstract;
use Shopbox\Models\Zpanel\Billing;


class BillTransformer extends TransformerAbstract
{
	public function transform(Billing $billing)
	{
		
		return [
			'id' => $billing->id,
			'reference' => $billing->reference,
			'amount' => $billing->total_payable,
			'date' => $billing->created_at->toFormattedDateString(),
			'status' => $billing->state
		];
	}
	
}