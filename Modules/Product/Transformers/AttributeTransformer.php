<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Attribute;

class AttributeTransformer extends TransformerAbstract
{
	public function transform(Attribute $attribute)
	{
		
		return [
			'id' => $attribute->id,
			'name' => $attribute->name,
			'display_name' => $attribute->public_name,
			'display_type' => $attribute->group_type,
			'display_style' => $attribute->display_style,
			'options' => $this->getAttributeOprions($attribute)
			
		];
	}

	protected function getAttributeOprions(Attribute $attribute)
	{
		$data = [];
		$options = $attribute->options;

		if($options->count()) {

			foreach ($options as $key => $option) {
				
				$data[$key]['id'] = $option->id;
				$data[$key]['name'] = $option->name;
				$data[$key]['type'] = $option->type;
				$data[$key]['color'] = $option->color;
				$data[$key]['pattern'] = $option->type === 'pattern' ? asset('stores').'/'.$attribute->store->domain.'/pattern/'.$option->pattern : '';

			}
		}

		return $data;
	}
	
}