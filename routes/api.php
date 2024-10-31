<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:admin-api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::middleware('auth:admin-api')->get('/pay', 'IPGController@index');

Route::group(['middleware' => 'auth:web,api', 'prefix' => 'storefront'], function () {

	Route::get('/customer', 'StoreFront\Customer\CustomerController@index');

});

// Route::group(['prefix' => 'products'], function () {

// 	Route::get('/', 'StoreFront\Product\ProductController@index');

// 	// Route::get('/{product}', 'StoreFront\Product\ProductController@show');

// 	// Route::get('/type/best-selling', 'StoreFront\Product\ProductController@bestSelling');

// 	// Route::get('/type/featured', 'StoreFront\Product\ProductController@featured');

// });

/*Route::group(['prefix' => 'menus'], function () {

	Route::get('/', 'StoreFront\Menu\MenuController@index');

	Route::get('/{menu}', 'StoreFront\Menu\MenuController@show');

});*/

Route::group(['prefix' => 'blogs'], function () {

	Route::get('/', 'StoreFront\Blog\BlogController@index');

});


