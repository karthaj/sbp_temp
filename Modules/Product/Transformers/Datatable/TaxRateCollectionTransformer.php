<?php

namespace Modules\Product\Transformers\Datatable;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Tax;
use Modules\Product\Entities\TaxClass;


class TaxRateCollectionTransformer extends TransformerAbstract
{
	public function transform(Tax $tax)
	{
		return [
			'id' => $tax->id,
			'zone' => $tax->zone->name,
			'name' => $tax->name,
			'rates' =>  $this->getTaxRates($tax),
			'priority' => $tax->priority,
			'status' => $tax->status,
			'edit' => route('tax.rates.edit', $tax)
		];
	}

	protected function getTaxRates(Tax $tax)
	{
		$data = [];
		$tax_classes = TaxClass::all();
		
		foreach ($tax_classes as $key => $class) {
			
			if($tax->rates()->where('tax_class_id', $class->id)->count()) {

				$tax_rate = $tax->rates()->where('tax_class_id', $class->id)->first();
				$data[$key]['class'] = $tax_rate->taxClass->name;
				$data[$key]['rate'] = $tax_rate->rate * 100;
			} else {

				$data[$key]['class'] = $class->name;
				$data[$key]['rate'] = '';
			}
		}

		return $data;
	}
	
}