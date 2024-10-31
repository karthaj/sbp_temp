export const getProducts = ({ commit }, endpoint) => {

	return axios.get(endpoint).then((response) => {

		commit('setProducts', response.data.data)
		return Promise.resolve()
		
	})
}

export const getCategories = ({ commit }, endpoint) => {

	return axios.get(endpoint).then((response) => {

		commit('setCategories', response.data)
		return Promise.resolve()
		
	})
}


// get cart

// add a product to a cart

// remove a product from our cart