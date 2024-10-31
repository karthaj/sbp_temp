<?php

namespace Shopbox\Transformers;

use Shopbox\Models\Front\StoreTheme;
use League\Fractal\TransformerAbstract;


class ThemeCollectionTransformer extends TransformerAbstract
{
	public function transform(StoreTheme $store_theme)
	{
		$theme = json_decode(file_get_contents(resource_path('views/stores/'.session('store')->domain.'/'.$store_theme->theme->slug.'/config/setting.json')), true);

	  	$update = json_decode(file_get_contents(storage_path('app/appconfig/themes/'.$store_theme->theme->slug.'/config/setting.json')), true);

		return [
			'id' => $store_theme->id,
			'alias' => $store_theme->theme->alias,
			'name' => $store_theme->theme->theme_name,
			'description' => $theme['meta']['description'],
			'author' => $theme['meta']['author_name'],
			'screenshot' => $this->themeScreenShot($store_theme, $theme['meta']),
			'update_version' => $update['version'],
			'current_version' => $store_theme->version,
			'active' => $store_theme->active,
			'has_updates' => $store_theme->version !== $update['version'] ? true : false,
			'customize_uri' => route('theme.editor',  $store_theme->theme->alias),
			'update_uri' => route('theme.update', $store_theme)
		];

	}

	protected function themeScreenShot(StoreTheme $store_theme, $theme)
	{
		if($theme['desktop_screenshot']) {
			return asset('stores/'.session('store')->domain.'/themes/'.$store_theme->theme->slug.'/meta/'.$theme['desktop_screenshot']);
		}

		return 'https://via.placeholder.com/920x720?text='.$store_theme->theme->theme_name;
	}	
	
}