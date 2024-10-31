<?php

namespace Shopbox\Transformers\Marketplace;

use Config;
use League\Fractal\TransformerAbstract;
use Shopbox\Models\Zpanel\Plugin;


class PluginTransformer extends TransformerAbstract
{
	public function transform(Plugin $plugin)
	{
		$data = Config::get($plugin->alias);

		return [
			'id' => $plugin->id,
			'handle' => $plugin->slug,
			'name' => $data['name'],
			'description' => $data['description'],
			'price' => $plugin->price,
			'author_name' => $data['author_name'],
			'author_email' => $data['author_email'],
			'author_url' => $data['author_url'],
			'cover' => $data['cover'] ? asset('modules/'.$plugin->alias.'/'.$data['cover']) : '',
			'screenshots' => $this->getPluginScreenshots($data['screenshots'], $plugin->alias),
			'eligible' => $plugin->plans->count() ? (bool) $plugin->plans->contains('plan_id', session('store')->plan_id) : true,
			'installed' => $this->pluginInstalled($plugin),
			'plans' => $this->getPluginPlans($plugin)
		];

	}

	protected function getPluginPlans(Plugin $plugin)
	{
		$plans = [];

		if($plugin->plans->count()) {
			foreach($plugin->plans as $plugin) {
				array_push($plans, [
					'handle' => $plugin->plan->slug,
					'name' => $plugin->plan->name,
				]);
			}
		}

		return $plans;
	}

	protected function pluginInstalled(Plugin $plugin)
	{
		if($plugin->plans->count()) {

			return (bool) $plugin->plans->contains('plan_id', session('store')->plan_id);
		}

		if($plugin->category->alias === 'payment') {

			return (bool) session('store')->payments->contains('plugin_id', $plugin->id);
		}

		return (bool) session('store')->plugins->contains('plugin_id', $plugin->id);
	}

	protected function getPluginScreenshots(array $screenshots, $plugin)
	{
		$data = [];

		if(count($screenshots)) {
			foreach ($screenshots as $screenshot) {
				array_push($data, asset('modules/'.$plugin.'/'.$screenshot));
			}
		}

		return $data;
	}

}