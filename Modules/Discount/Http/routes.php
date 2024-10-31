<?php

Route::group(['prefix' => 'merchant/discounts', 'middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'namespace' => 'Modules\Discount\Http\Controllers', 'as' => 'discounts.'], function()
{
    Route::group(['middleware' => 'permission:view discounts'], function () {

    	Route::resource('/datatable', 'DataTable\DiscountController');

    	Route::get('/', 'DiscountController@index')->name('index');
    	
    });

    Route::group(['middleware' => 'permission:add discounts'], function () {

    	Route::get('/new', 'DiscountController@create')->name('create');

		Route::post('/new', 'DiscountController@store')->name('store');

    });	

    Route::group(['middleware' => 'permission:edit discounts'], function () {

    	Route::get('/{discount}/edit', 'DiscountController@edit')->name('edit');

		Route::patch('/{discount}/edit', 'DiscountController@update')->name('update');

    });  

});
