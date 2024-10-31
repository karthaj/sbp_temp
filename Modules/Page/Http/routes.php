<?php

Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'prefix' => 'merchant/pages', 'namespace' => 'Modules\Page\Http\Controllers', 'as' => 'pages.'], function()
{
	Route::group(['middleware' => 'permission:view pages'], function () {

		Route::resource('/datatable', 'DataTable\PageController');

	 	Route::get('/', 'PageController@index')->name('index');

	});

	Route::group(['middleware' => 'permission:add pages'], function () {

		Route::get('/new', 'PageController@create')->name('create');

	 	Route::post('/new', 'PageController@store')->name('store');

	});

	Route::group(['middleware' => 'permission:edit pages'], function () {

		Route::get('/{page}/edit', 'PageController@edit')->name('edit');

		Route::patch('/{page}/edit', 'PageController@update')->name('update');

	});
   
});
