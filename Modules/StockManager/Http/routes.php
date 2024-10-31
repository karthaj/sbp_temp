<?php

Route::group(['middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'prefix' => 'stockmanager', 'as' => 'stockmanager.', 'namespace' => 'Modules\StockManager\Http\Controllers'], function()
{
    Route::get('/stocks', 'StockManagerController@index')->name('index');
});
