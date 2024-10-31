<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Transfer;

class TransferCollectionTransformer extends TransformerAbstract
{

	public function transform(Transfer $transfer)
	{
		return [
			'id' => $transfer->reference,
			'store' => $transfer->store_location->name,
			'type' => $transfer->type,
			'status' => $transfer->status,
			'created_at' => $transfer->created_at_tz->toDateTimeString()
		];
	}
	
}