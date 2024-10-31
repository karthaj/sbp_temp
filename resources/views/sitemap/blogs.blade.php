<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($blogs as $blog)
	<url>
		<loc>
			{{ getStoreUrl($blog->store) }}/blogs/{{ $blog->slug }}
		</loc>
		<lastmod>{{ $blog->updated_at->toAtomString() }}</lastmod>
		<changefreq>weekly</changefreq>
		@if($blog->featured_image)
		<image:image>
			<image:loc>
				{{ getStoreUrl($blog->store).'/stores/'.$blog->store->domain.'/blog/'.$blog->featured_image }}
			</image:loc>
			<image:title>{{ $blog->title }}</image:title>
		</image:image>
		@endif
	</url>
@endforeach
</urlset>