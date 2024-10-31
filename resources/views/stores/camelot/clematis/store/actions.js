import Cookies from 'js-cookie'
import bus from '../assets/js/bus'

export const getCart = ({ commit }, payload) => {

	var cart = Cookies.get('cart');

	if (typeof cart != 'undefined') {
        
		return axios.get('/cart.json').then((response) => {

    		commit('setCart', response.data)
        	commit('setLoading', false)
        	bus.$emit('cart.update', response.data)

    		return Promise.resolve()
    		
    	})
	}
    commit('setLoading', false)
}

export const removeItemFromCart = ({ dispatch, commit, state }, id) => {

    commit('setLoading', true)
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