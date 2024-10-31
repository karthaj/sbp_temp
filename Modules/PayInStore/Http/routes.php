<?php

Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'prefix' => 'merchant/store/payments', 'namespace' => 'Modules\PayInStore\Http\Controllers', 'as' => 'payinstore.'], function()
{
	Route::group(['middleware' => 'permission:setup pay in store'], function() {

		Route::get('payinstore', 'PayInStoreController@edit')->name('edit');

    	Route::post('payinstore', 'PayInStoreController@store')->name('store');

	});

});
