import Cookies from 'js-cookie'

export const getCart = ({ commit }, payload) => {

	var cart = Cookies.get('cart');
	
	if (cart) {
		return axios.get('/cart.json').then((response) => {

			commit('setCart', response.data)

			return Promise.resolve()
			
		})
	}
	commit('setLoading', false)
}

export const removeItemFromCart = ({ dispatch, commit, state }, id) => {

	state.cart.items = state.cart.items.filter((item) => {
		return item.id !== id
	})

	return axios.post('/cart/update', {
        id: id,
        qty: 0
    }).then((response) => { 
    	dispatch('getCart');
    	return Promise.resolve();
    }).catch((error) => {
      console.log(error)
    })

}
