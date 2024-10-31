<?php

// store routes

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


Route::group(['as' => 'stores.'], function () { 

	Route::get('/sitemap.xml', 'Sitemap\SitemapController@index');

	Route::get('/products_sitemap.xml', 'Sitemap\SitemapController@products');

	Route::get('/pages_sitemap.xml', 'Sitemap\SitemapController@pages');

	Route::get('/categories_sitemap.xml', 'Sitemap\SitemapController@categories');

	Route::get('/blogs_sitemap.xml', 'Sitemap\SitemapController@blogs');

	Route::get('/', 'Front\StoreController@home')->name('home');

	// Route::get('/products', 'Front\StoreController@categoryIndex')->name('product.index');

	Route::get('/categories', 'Front\StoreController@categoryIndex')->name('categories.index');

	Route::get('/brands', 'Front\StoreController@brandIndex')->name('brands.index');

	Route::get('/blogs', 'Front\StoreController@blogs')->name('blogs');

	Route::get('/blogs/{blog}', 'Front\StoreController@blog')->name('blog');

	Route::get('/categories/{category}', 'Front\StoreController@category')->name('categories.category');

	Route::get('/brands/{brand}', 'Front\StoreController@brand')->name('brands.brand');

	Route::get('/products/{product}', 'Front\StoreController@show')->name('product.show');

	Route::get('pages/{page}', 'Front\StoreController@showPage')->name('page');

	Route::post('pages/{page}', 'Front\StoreController@send')->name('contact');

	Route::get('/store/password', 'Front\StoreController@showStorePasswordForm')->name('password');

	Route::post('/store/password', 'Front\StoreController@verifyStorePassword')->name('password.verify');

	Route::get('/search', 'Front\StoreController@searchIndex')->name('.search');

});	

Route::group(['prefix' => 'account', 'as' => 'customer.', 'middleware' => 'auth'], function () { 

	Route::get('/', 'Front\Customer\AccountController@index')->name('profile')->middleware(['account', 'agreement']);

	Route::get('/agreement', 'Front\Customer\AccountController@agreement')->name('agreement');

	Route::post('/agreement', 'Front\Customer\AccountController@accept')->name('accept.agreement');

	Route::post('/update', 'Front\Customer\AccountController@update')->name('account.update');

	Route::post('/avatar', 'Front\Customer\AccountController@avatar')->name('avatar.upload');

	Route::get('/stores/{store}/unsubscribe', 'Front\Customer\AccountController@unsubscribe')->name('account.unsubscribe');

	Route::post('/address/add', 'Front\Customer\AccountController@store')->name('address.store');

	Route::delete('/addresses/{address}/delete', 'Front\Customer\AccountController@destroy')->name('address.delete');

	Route::post('/orders/{order}/return', 'Front\ReturnController@store')->name('order.return');

});

Route::group(['prefix' => 'wishlist', 'as' => 'wishlist.', 'middleware' => 'auth'], function () { 

	Route::post('/', 'Front\Wishlist\WishlistController@store')->name('store.item');

	Route::post('/item/remove', 'Front\Wishlist\WishlistController@destroy')->name('remove.item');

});


Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () { 

	Route::get('/', 'Front\CartController@index')->name('index');

	Route::post('/add', 'Front\CartController@store')->name('add');

	// Route::post('/get-cart', 'Front\CartController@show')->name('show');

	Route::post('/update', 'Front\CartController@update')->name('update');

	Route::get('/{cart}/recover', 'Front\CartController@recover')->name('recover');

	Route::get('/{cart}/reserve', 'Front\CartController@reserveStock');

});

Route::get('/cart.json', 'Front\CartController@show')->name('show');

Route::get('/countries.json', 'CountryController@index');

Route::get('/checkout/payments', 'Front\CheckoutController@payments');

Route::group(['prefix' => 'checkout', 'as' => 'checkout.'], function () { 

	Route::get('/{cart}', 'Front\CheckoutController@show');

	Route::get('{cart}/shipping_quotes.json', 'Front\CheckoutController@shippingRates');

	Route::group(['middleware' => 'checkout'], function () { 

		Route::get('/', 'Front\CheckoutController@index')->name('index');

		Route::post('{cart}/customer', 'Front\CheckoutController@customer');

		Route::post('{cart}/address', 'Front\CheckoutController@address');

		Route::put('{cart}/shipping', 'Front\CheckoutController@shipping');

		Route::post('{cart}/shipping', 'Front\CheckoutController@update');

		Route::post('/{cart}/place-order', 'Front\OrderController@createOrder')->name('checkout.order');

		Route::post('{cart}/discounts', 'Front\CheckoutController@discount');

		Route::delete('{cart}/discounts/{discount}', 'Front\CheckoutController@removeDiscount');

		Route::post('/customer', 'Front\CheckoutController@authenticate');

		Route::delete('/customer', 'Front\CheckoutController@logout');

		Route::patch('{cart}/update', 'Front\CheckoutController@destroy');

		Route::put('{cart}/payment', 'Front\CheckoutController@payment');

		// New Route for OTP Verification
		Route::post('{cart}/verify-otp', 'Front\CheckoutController@verifyOtp')->name('verifyOtp');
 

	});

	// Route::post('/', 'Front\CheckoutController@getCheckoutData');

	// Route::post('/customer', 'Front\CheckoutController@customer')->name('customer');

	// Route::post('/auth/customer', 'Front\CheckoutController@authenticate')->name('auth.customer');

	// Route::post('/auth/customer/logout', 'Front\CheckoutController@logout')->name('customer.logout');

	// Route::post('/auth/customer/addresses', 'Front\CheckoutController@getAddresses')->name('customer.addresses');

	// Route::post('/auth/customer/address', 'Front\CheckoutController@getAddress')->name('customer.address');

	// Route::post('/customer/address', 'Front\CheckoutController@storeAddress')->name('address');

	// Route::post('/shipping', 'Front\CheckoutController@storeShipping')->name('shipping');

	// Route::post('/discount', 'Front\CheckoutController@cartDiscount')->name('discount');

	// Route::post('/remove-discount', 'Front\CheckoutController@removeDiscount')->name('discount.remove');

	// Route::post('/get-shipping', 'Front\CheckoutController@getShipping')->name('discount.shipping');

	// Route::post('/country/states', 'Front\CheckoutController@getStates')->name('country.states');

	// Route::post('/country/cities', 'Front\CheckoutController@getCities')->name('country.cities');

	// Route::post('/country/city/postcode', 'Front\CheckoutController@getCityPostCode')->name('country.city.postcode');

});

Route::get('/order-confirmation/{order}', 'Front\OrderController@show')->name('checkout.order.confirmation');

Route::post('/order-confirmation/{order}/customer', 'Front\OrderController@createCustomer')->name('checkout.customer.create');

Route::get('/track/visitor', 'Front\VisitorController@index')->name('track.visit');

Route::get('/countries/{country}/states', 'CountryController@states');

// Route::get('/user', 'Front\Customer\IndexController@index')->middleware('auth');

Route::group(['prefix' => 'api/products'], function () {

	// Route::get('/', 'StoreFront\Product\ProductController@index');
	
	Route::get('/{product}', 'StoreFront\Product\ProductController@show');

});

Route::group(['prefix' => 'api/categories'], function () {

	Route::get('/', 'StoreFront\Category\CategoryController@index');

	//Route::get('/{category}', 'StoreFront\Category\CategoryController@show');

	Route::get('/{category}/products', 'StoreFront\Category\CategoryController@getProducts');

});

Route::group(['prefix' => 'api/brands'], function () {

	Route::get('/', 'StoreFront\Brand\BrandController@index');

	Route::get('/{brand}/products', 'StoreFront\Brand\BrandController@getProducts');

});

Route::get('/api/blogs', 'StoreFront\Blog\BlogController@index');

Route::get('/orders/{order}/download_item/{file}', 'StoreFront\File\FileController@index')->name('download.item');