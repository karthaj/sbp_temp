<?php

Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'prefix' => 'merchant/orders', 'namespace' => 'Modules\Order\Http\Controllers', 'as' => 'orders.'], function()
{
	Route::group(['middleware' => 'permission:view orders'], function () {

		Route::get('/', 'OrderController@index')->name('index');

		Route::resource('/datatable', 'DataTable\OrderController');

	});
    
	Route::group(['middleware' => 'permission:edit orders'], function () {
		
		Route::get('/{order}/view', 'OrderController@edit')->name('edit');

		Route::post('/{order}/tracking-number', 'OrderController@update')->name('tracking.number');

		Route::post('/{order}/payment', 'OrderController@updatePayment')->name('payment');

		Route::post('/{order}/status', 'OrderController@updateStatus')->name('status');

	});

	Route::group(['middleware' => 'permission:view carts'], function () {

		Route::resource('/abandoned-carts/datatable', 'DataTable\CartController');

		Route::get('/abandoned-carts', 'CartController@index')->name('abandoned.carts.index');

	});

	Route::group(['middleware' => 'permission:send recover notification'], function () {

		Route::patch('/abandoned-carts/{cart}/email/send', 'CartController@recover')->name('abandoned.carts.recovery.email');

	});

	Route::group(['middleware' => 'permission:edit carts'], function () {

		Route::get('/abandoned-carts/{cart}', 'CartController@edit')->name('abandoned.carts.edit');

	});

	Route::group(['middleware' => 'permission:edit carts'], function () {

		Route::delete('/{cart}/delete', 'CartController@destroy')->name('abandoned.carts.destroy');

	});

	Route::post('/{cart}/restock', 'CartController@restockProducts')->name('abandoned.cart.restock');

	Route::group(['middleware' => 'permission:view returns'], function () {

		Route::get('/returns', 'ReturnController@index')->name('return.index');

		Route::resource('returns/datatable', 'DataTable\ReturnController');

	});

	Route::group(['middleware' => 'permission:edit returns'], function () {

		Route::get('/returns/{return}/view', 'ReturnController@edit')->name('return.edit');

		Route::patch('/returns/{return}/view', 'ReturnController@update')->name('return.update');

	});

	Route::group(['middleware' => 'permission:add returns'], function () {

		Route::get('/returns/create', 'ReturnController@create')->name('return.create');

		Route::post('/returns/create', 'ReturnController@store')->name('return.store');

	});

	Route::get('/return/invoice', 'ReturnController@show')->name('return.invoice');

	Route::post('/export', 'ExportController@export')->name('export');

	Route::get('/{store}/download/export', 'ExportController@download')->name('export.download');

});
