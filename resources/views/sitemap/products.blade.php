<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($products as $product)
	<url>
		<loc>
			{{ getStoreUrl($product->store) }}/products/{{ $product->slug }}
		</loc>
		<lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
		<changefreq>daily</changefreq>
		@if($product->images->where('cover', 1)->count())
		<image:image>
			<image:loc>
				{{ getStoreUrl($product->store).'/stores/'.$product->store->domain.'/product/'.$product->images->where('cover', 1)->first()->large_default }}
			</image:loc>
			<image:title>{{ $product->name }}</image:title>
		</image:image>
		@endif
	</url>
@endforeach
</urlset>