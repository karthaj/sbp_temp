export default {
	
	shopbox: {
		store: {
			name: Shopbox.store,
			url: Shopbox.store_url,
			currency: Shopbox.currency,
			imagePath: Shopbox.store_url + '/stores/' + Shopbox.domain + '/img/',
			patternPath: Shopbox.store_url + '/stores/' + Shopbox.domain + '/pattern/',
			categoryPath: Shopbox.store_url + '/stores/' + Shopbox.domain + '/category/',
			assetsPath: Shopbox.store_url + '/stores/' + Shopbox.domain + '/themes/clematis/assets/img/'
		}
	},
	defaultImg: Shopbox.store_url + '/assets/img/ProductDefault.gif',
	settings: Theme.settings,
	cart: {},
	loading: true
	
}