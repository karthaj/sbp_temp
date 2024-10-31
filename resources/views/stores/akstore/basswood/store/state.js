export default {
	
	shopbox: {
		store: {
			name: Shopbox.store,
			url: Shopbox.store_url,
			currency: Shopbox.currency,
			imagePath: Shopbox.store_url + '/stores/' + Shopbox.domain + '/img/',
			categoryPath: Shopbox.store_url + '/stores/' + Shopbox.domain + '/category/'
		}
	},
	defaultImg: Shopbox.store_url + '/assets/img/ProductDefault.gif',
	settings: Theme.settings,
	cart: {},
	loading: true
	
}