<?php

namespace Shopbox\Transformers\Dropdown;

use League\Fractal\TransformerAbstract;
use Shopbox\Models\Zpanel\GFont;


class FontTransformer extends TransformerAbstract
{
	public function transform(GFont $font)
	{
		
		return [
			'name' => $font->name,
			'handle' => $font->value,
			'family' => $font->family,
			'variation' => $font->variation,
			'fallbacks' => $font->fallbacks
			
		];
	}
	
}