<?php

namespace Shopbox\Transformers\StoreFront;

use Modules\Product\Traits\HasVariation;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Combination;
use League\Fractal\TransformerAbstract;


class OptionsTransformer extends TransformerAbstract
{
	use HasVariation;

	protected $attribute_id;
	protected $option_id;

	public function __construct($group = 0, $selected = 0)
	{
		$this->attribute_id = $group;
		$this->option_id = $selected;
	}

	public function transform(Product $product)
	{
		
		$options = [];

		if($product->variations->count()) {

			foreach($product->variations as $variation) {

				foreach($variation->combinations as $index => $combination) { 
					$options[$combination->option->attribute_id]['id'] = $combination->option->attribute_id;
					$options[$combination->option->attribute_id]['type'] = $combination->option->attribute->display_style;
					$options[$combination->option->attribute_id]['name'] = $combination->option->attribute->public_name;
					$options[$combination->option->attribute_id]['values'][$combination->option_id]['id'] = $combination->option->id;
					$options[$combination->option->attribute_id]['values'][$combination->option_id]['option_id'] = $combination->option->attribute_id;
					$options[$combination->option->attribute_id]['values'][$combination->option_id]['name'] = $combination->option->name;

					if($combination->option->attribute->display_style === '[CS]') {
						$options[$combination->option->attribute_id]['values'][$combination->option_id]['color'] = $combination->option->color;
						$options[$combination->option->attribute_id]['values'][$combination->option_id]['pattern'] = $combination->option->pattern;
					}

				}

			}

			foreach($options as $index => $option) {
				$options[$index]['values'] = array_values($option['values']);
			}
	
		}
		
		return array_values($options);

	}

}