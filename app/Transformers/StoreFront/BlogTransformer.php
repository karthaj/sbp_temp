<?php

namespace Shopbox\Transformers\StoreFront;

use League\Fractal\TransformerAbstract;
use Modules\Blog\Entities\Blog;


class BlogTransformer extends TransformerAbstract
{
	public function transform(Blog $blog)
	{
		return [
			'id' => $blog->id,
			'title' => $blog->title,
			'handle' => $blog->slug,
			'url' => route('stores.blog', $blog),
			'content' => html_entity_decode($blog->content),
			'featured_image' => $blog->featured_image ? asset('stores/'.session('store')->domain.'/blog/'.$blog->featured_image) : '',
			'author' => $blog->author,
			'date' => $blog->created_at_tz
		];
	}
	
}