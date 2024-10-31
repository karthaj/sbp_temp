<?php

Route::group(['prefix' => 'merchant/customers', 'middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'namespace' => 'Modules\Customer\Http\Controllers', 'as' => 'customers.'], function()
{
    
	Route::group(['middleware' => 'permission:view customers'], function () {

		Route::resource('/datatable', 'DataTable\CustomerController');

		Route::get('/', 'CustomerController@index')->name('index');

	});

	Route::group(['middleware' => 'permission:add customers'], function () {

		Route::get('/add', 'CustomerController@create')->name('add');

		Route::post('/add', 'CustomerController@store')->name('store');

	});

	Route::group(['middleware' => 'permission:edit customers'], function () {

		Route::get('/{customer}/edit', 'CustomerController@edit')->name('edit');

		Route::patch('/{customer}/edit', 'CustomerController@update')->name('update');

	});

		
	Route::group(['middleware' => 'permission:view customer group'], function () {

		Route::resource('/groups/datatable', 'DataTable\GroupController');

		Route::get('/groups', 'GroupController@index')->name('groups.index');

	});

	Route::group(['middleware' => 'permission:add customer group'], function () {

		Route::get('/groups/add', 'GroupController@create')->name('groups.add');
		
		Route::post('/groups/add', 'GroupController@store')->name('groups.store');

	});

	Route::group(['middleware' => 'permission:edit customer group'], function () {

		Route::get('/groups/{group}/edit', 'GroupController@edit')->name('groups.edit');

		Route::patch('/groups/{group}/edit', 'GroupController@update')->name('groups.update');

	});

	Route::get('/address/country/states', 'CustomerController@getStates')->name('address.country.states');

});
