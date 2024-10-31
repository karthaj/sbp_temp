export const getProducts = ({ commit }) => {

	return axios.get('/merchant/products/dropdown.json').then((response) => {

		commit('setProducts', response.data)
		return Promise.resolve()
		
	})
}

export const getBrands = ({ commit }) => {

	return axios.get('/merchant/brands/dropdown.json').then((response) => {

		commit('setBrands', response.data)
		return Promise.resolve()
		
	})
}

export const getMenus = ({ commit }) => {

	return axios.get('/merchant/linklists/dropdown.json').then((response) => {

		commit('setMenus', response.data)
		return Promise.resolve()
		
	})
}

export const getCategories = ({ commit }) => {

	return axios.get('/merchant/collections/dropdown.json').then((response) => {

		commit('setCategories', response.data)
		return Promise.resolve()
		
	})
}