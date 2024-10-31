<?php

Route::group(['middleware' => 'web', 'prefix' => 'emailtemplate', 'namespace' => 'Modules\EmailTemplate\Http\Controllers'], function()
{
    Route::get('/', 'EmailTemplateController@index');
});
