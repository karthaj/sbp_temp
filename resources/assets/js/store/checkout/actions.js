export const getCheckout = ({ commit, state }) => {

	return axios.get(`/checkout/${state.checkout_id}`).then((response) => {

		commit('setCheckout', response.data)
		commit('auth', !response.data.customer.guest)
		return Promise.resolve(response.data)
		
	})
}

export const getCountries = ({ commit }) => {

	return axios.get('/countries.json').then((response) => {

		commit('setCountries', response.data.data)
		return Promise.resolve()
		
	})
}