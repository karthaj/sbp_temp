<?php

Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'prefix' => 'merchant/store/payments', 'namespace' => 'Modules\BankTransfer\Http\Controllers', 'as' => 'bank.transfer.'], function()
{
	Route::group(['middleware' => 'permission:setup bank transfer'], function() {

		Route::get('banktransfer', 'BankTransferController@edit')->name('edit');

    	Route::post('banktransfer', 'BankTransferController@store')->name('store');

	});
   
});
