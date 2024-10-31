<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Transfer;

class TransferStatusTransformer extends TransformerAbstract
{

	public function transform(Transfer $transfer)
	{
		
		return [
			'reference' => $transfer->reference,
			'from' => $transfer->store->store_name,
			'to' => $transfer->store_location->name,
			'status' => $transfer->status,
			'remarks' => $transfer->remarks
		];
	}
	
}