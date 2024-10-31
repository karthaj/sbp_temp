<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($pages as $page)
	<url>
		<loc>
			{{ getStoreUrl($page->store) }}/pages/{{ $page->slug }}
		</loc>
		<lastmod>{{ $page->updated_at->toAtomString() }}</lastmod>
		<changefreq>weekly</changefreq>
	</url>
@endforeach
</urlset>