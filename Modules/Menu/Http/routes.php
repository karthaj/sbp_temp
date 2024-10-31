<?php

Route::group(['prefix' => 'merchant/menus', 'middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'as' => 'menus.', 'namespace' => 'Modules\Menu\Http\Controllers'], function()
{

	Route::group(['middleware' => 'permission:view menus'], function () {

		Route::get('/', 'MenuController@index')->name('index');

	});

	Route::group(['middleware' => 'permission:add menus'], function () {

		Route::get('/new', 'MenuController@create')->name('create');

		Route::post('/new', 'MenuController@store')->name('store');

	});

	Route::group(['middleware' => 'permission:edit menus'], function () {

		Route::get('/{menu}', 'MenuController@edit')->name('edit');

	    Route::patch('/{menu}', 'MenuController@update')->name('update');

	});	    

	Route::group(['middleware' => 'permission:delete menus'], function () {

		 Route::delete('/{menu}', 'MenuController@destroy')->name('destroy');

	});
	    
	    
    Route::post('/items/{menu}', 'MenuController@storeItems');

    Route::post('/item/save', 'MenuController@storeItem');

    Route::delete('/items/{item}/delete', 'MenuController@destroyItem');

	Route::post('/item/nest', 'MenuController@nestItem');
   
});
