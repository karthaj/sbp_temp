<?php

Route::group(['namespace' => 'Modules\HNB\Http\Controllers'], function()
{
    Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'prefix' => 'merchant/store/payments', 'as' => 'hnb.'], function () {

    	Route::group(['middleware' => 'permission:setup hnb'], function() {

    		Route::get('hnb', 'HNBController@edit')->name('edit');

    		Route::post('hnb', 'HNBController@store')->name('store');

    	});

    });

    Route::post('hnb/response', 'ResponseController@index')->name('response.hnb');

    Route::group(['middleware' => ['web', 'bindings']], function () {

    	Route::get('/connect/hnb/{order}', 'HNBController@show')->name('connect.hnb');

    });
});