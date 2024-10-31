<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($categories as $category)
	<url>
		<loc>
			{{ getStoreUrl($category->store) }}/categories/{{ $category->slug }}
		</loc>
		<lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
		<changefreq>daily</changefreq>
		@if($category->cover_image)
		<image:image>
			<image:loc>
				{{ getStoreUrl($category->store).'/stores/'.$category->store->domain.'/category/'.$category->cover_image }}
			</image:loc>
			<image:title>{{ $category->name }}</image:title>
		</image:image>
		@endif
	</url>
@endforeach
</urlset>