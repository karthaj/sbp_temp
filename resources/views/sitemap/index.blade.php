<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<!-- This is the parent sitemap linking to additional sitemaps for products, categories and pages as shown below. The sitemap is synced automatically. -->
	<sitemap>
		<loc>
			{{ getStoreUrl($store) }}/products_sitemap.xml
		</loc>
	</sitemap>
	<sitemap>
		<loc>
			{{ getStoreUrl($store) }}/pages_sitemap.xml
		</loc>
	</sitemap>
	<sitemap>
		<loc>
			{{ getStoreUrl($store) }}/categories_sitemap.xml
		</loc>
	</sitemap>
	<sitemap>
		<loc>
			{{ getStoreUrl($store) }}/blogs_sitemap.xml
		</loc>
	</sitemap>
</sitemapindex>