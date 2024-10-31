<?php

namespace Modules\Order\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Order\Entities\OrderReturn;


class ReturnCollectionTransformer extends TransformerAbstract
{
	public function transform(OrderReturn $return)
	{
	
		return [	
			'id' => $return->return_id,
			'return_url' => route('orders.return.edit', $return),
			'order' => $return->order->order_id,
			'order_url' => route('orders.edit', $return->order),
			'date' => $return->created_at->toFormattedDateString(),
			'status' => $return->status->name,
		];
	}
	
}