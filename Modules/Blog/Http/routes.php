<?php

Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'prefix' => 'merchant/blogs', 'namespace' => 'Modules\Blog\Http\Controllers', 'as' => 'blogs.'], function()
{

	Route::group(['middleware' => 'permission:view blogs'], function () {

		Route::resource('/datatable', 'DataTable\BlogController');

    	Route::get('/', 'BlogController@index')->name('index');

	});
	
	Route::group(['middleware' => 'permission:add blogs'], function () {

		Route::get('/new', 'BlogController@create')->name('create');

		Route::post('/new', 'BlogController@store')->name('store');

	});

    Route::group(['middleware' => 'permission:edit pages'], function () {

    	Route::get('/{blog}/edit', 'BlogController@edit')->name('edit');

		Route::patch('/{blog}/edit', 'BlogController@update')->name('update');

    });

    Route::delete('{blog}/image/remove', 'BlogController@destroy')->name('image.destroy');

});
