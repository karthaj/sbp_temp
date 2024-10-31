
export const setDirty = (state, value) => {

	state.dirty = value

}

export const setProducts = (state, products) => {

	state.products = products

}

export const setBrands = (state, brands) => {

	state.brands = brands

}

export const setMenus = (state, menus) => {

	state.menus = menus

}

export const setCategories = (state, categories) => {

	state.categories = categories

}

export const setSettings = (state, settings) => {

	state.settings = settings

}

export const setThemeSettings = (state, settings) => {

	state.themeSettings = settings

}

export const setSections = (state, sections) => {

	state.sections = sections

}

export const setShopbox = (state, shopbox) => {

	state.shopbox.store = shopbox.store
	state.shopbox.domain = shopbox.domain
	state.shopbox.store_url = shopbox.store_url
	state.shopbox.theme = shopbox.theme

}