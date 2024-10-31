<?php

namespace Modules\Order\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Order\Entities\OrderDetail;


class ReturnInvoiceTransformer extends TransformerAbstract
{
	public function transform(OrderDetail $item)
	{
	
		return [	
			'id' => $item->id,
			'product_name' => $item->product_name,
			'sku' => $item->product_sku ?: 'n/a',
			'quantity' => $item->product_quantity,
			'qty_to_return' => 1
		];
	}
	
}