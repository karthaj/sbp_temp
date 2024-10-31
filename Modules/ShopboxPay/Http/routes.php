<?php

Route::group(['namespace' => 'Modules\ShopboxPay\Http\Controllers'], function()
{
    Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'prefix' => 'merchant/store/payments', 'as' => 'shopboxpay.'], function () {

    	Route::group(['middleware' => 'permission:setup shopboxpay'], function() {

    		Route::get('shopbox-pay', 'ShopboxPayController@edit')->name('edit');

    		Route::patch('shopbox-pay', 'ShopboxPayController@update')->name('update');

    	});

    });

    Route::post('/shopboxpay/response', 'ResponseController@index')->name('shopboxpay.response');

    Route::post('sbpay/merchant/response', 'MerchantResponseController@index');

    Route::group(['middleware' => ['web', 'bindings']], function () {

    	Route::get('/connect/shopboxpay/{order}', 'ShopboxPayController@show')->name('connect.shopboxpay');

    });

});

