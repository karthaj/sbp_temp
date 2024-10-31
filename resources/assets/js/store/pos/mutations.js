export const setProducts = (state, products) => {

	state.products = products
	state.filteredProducts = products

}

export const setCategories = (state, categories) => {

	state.categories = categories

}

export const filterProducts = (state, query) => {

	if(!query) {

		state.filterProducts = state.products

	}

	state.filteredProducts = state.products.filter((row) => {
        return Object.keys(row).some((key) => {
            return String(row[key]).toLowerCase().indexOf(query.toLowerCase()) > -1
        })
    })

}

// set cart

// clear cart

// remove from cart

// append to cart