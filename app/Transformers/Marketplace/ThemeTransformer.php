<?php

namespace Shopbox\Transformers\Marketplace;

use League\Fractal\TransformerAbstract;
use Shopbox\Models\Front\Theme;


class ThemeTransformer extends TransformerAbstract
{
	public function transform(Theme $theme)
	{
		$data = json_decode(file_get_contents(storage_path('app/appconfig/themes/'.$theme->slug.'/config/setting.json')), true);

		return [
			'id' => $theme->id,
			'theme_id' => $theme->alias,
			'name' => $theme->theme_name,
			'description' => $data['meta']['description'],
			'desktop_screenshot' => $data['meta']['desktop_screenshot'] ? asset('themes/'.$theme->slug.'/'.$data['meta']['desktop_screenshot']) : '',
			'price' => $theme->price > 0 ? $theme->formattedPrice : 'free',
			'version' => $data['version'],
			'author_name' => $data['meta']['author_name'],
			'author_email' => $data['meta']['author_email'],
			'author_url' => $data['meta']['author_url'],
			'variation_count' => $theme->industries->count(),
			'variations' => $this->getThemeVariations($theme->industries),
			'features' => $data['meta']['features'],
			'purchased' => session()->has('store') ? (bool) session('store')->themes->contains('theme_id', $theme->id) : false
		];

	}
	
	protected function getThemeVariations($variations)
	{
		$data = [];

		if($variations->count()) {
			foreach($variations as $variation) {
				array_push($data, [
					'name' => $variation->pivot->variation,
					'screenshot' => [
						'mobile' => $variation->pivot->mobile_screenshot,
						'tab' => $variation->pivot->tab_screenshot,
						'desktop' => $variation->pivot->desktop_screenshot
					],
					'accent' => $variation->pivot->color,
					'demo_url' => $variation->pivot->preview_url
				]);
			}
		}

		return $data;
	}
}