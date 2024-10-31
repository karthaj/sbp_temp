<?php

Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'namespace' => 'Modules\CashOnDelivery\Http\Controllers', 'prefix' => 'merchant/store', 'as' => 'cod.'], function()
{

	Route::group(['middleware' => 'permission:view cod'], function() {

		Route::resource('/datatable/cashondeliveries', 'DataTable\PaymentController');

		Route::get('/payments/cashondelivery', 'PaymentController@index')->name('index');

	});

	Route::group(['middleware' => 'permission:add cod'], function() {

		Route::get('/payments/cashondelivery/add', 'PaymentController@create')->name('create');

    	Route::post('/payments/cashondelivery', 'PaymentController@store')->name('store');

	});
	
	Route::group(['middleware' => 'permission:edit cod'], function() {

		Route::get('/payments/cashondelivery/{cod}/edit', 'PaymentController@edit')->name('edit');

    	Route::patch('/payments/cashondelivery/{cod}/update', 'PaymentController@update')->name('update');
    	
	});
   
});
