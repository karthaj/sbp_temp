<?php

//Auth::routes();


Route::group(['prefix' => 'merchant', 'as' => 'admin.', 'middleware' => 'auth.subdomain'], function () {
	
	Route::group(['middleware' => 'auth.subdomain'], function () {
		// Authentication Routes...
		Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('login');
		Route::post('login', 'Auth\AdminLoginController@login');
		
		// Registration Routes...
		Route::get('register', 'Auth\AdminRegisterController@showRegistrationForm')->name('register');
		Route::post('register', 'Auth\AdminRegisterController@register');
	});
	
    Route::post('logout', 'Auth\AdminLoginController@logout')->name('logout');

    // Password Reset Routes...
    Route::get('password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\AdminResetPasswordController@reset');

    Route::get('/manage/store', 'HomeController@index');

});

Route::get('check-availability', 'AvailabilityController@index');

Route::get('unauthorized', 'Auth\UnauthorizedController@unauthorize');

Route::get('/landing', 'Front\HomeController@index')->name('landing');

Route::group(['prefix' => 'activation', 'as' => 'activation.', 'middleware' => ['guest:admin']], function () {

	Route::get('/resend', 'Auth\ActivationResendController@index')->name('resend');

	Route::post('/resend', 'Auth\ActivationResendController@store')->name('resend.store');

    Route::get('/{confirmation_token}', 'Auth\ActivationController@activate')->name('activate');

    Route::get('/active/{confirmation_token}', 'Zpanel\Account\ActivationController@index')->name('user.activate');

    Route::post('/password/{confirmation_token}', 'Zpanel\Account\ActivationController@activate')->name('user.password.store');

});

Route::group(['prefix' => 'verification', 'as' => 'verification.', 'middleware' => ['shop', 'guest']], function () {

	Route::get('/resend', 'Front\Auth\VerificationResendController@index')->name('resend');

	Route::post('/resend', 'Front\Auth\VerificationResendController@store')->name('resend.store');

	Route::get('/{verification_token}', 'Front\Auth\VerificationController@index')->name('index');

    Route::patch('/{verification_token}/verify', 'Front\Auth\VerificationController@update')->name('verify');

});

// Route::group(['prefix' => 'account', 'as' => 'customer.', 'middleware' => ['guest']], function () {

//     //Route::get('/resend', 'Auth\ActivationResendController@index')->name('resend');

//     //Route::post('/resend', 'Auth\ActivationResendController@store')->name('resend.store');

//     Route::get('/verification/{verification_token}', 'Auth\VerificationController@verify')->name('account.verify');

// });


Route::group(['prefix' => 'modules', 'middleware' => ['auth:admin'], 'as' => 'plugin.'], function () { 

    Route::get('/add', 'Zpanel\PluginController@create')->name('add');

    Route::get('/all', 'Zpanel\PluginController@index')->name('all');

    Route::post('/upload', 'Zpanel\PluginController@upload');

    Route::patch('/active/{id}', 'Zpanel\PluginController@activate')->name('active');

    Route::patch('/deactive/{id}', 'Zpanel\PluginController@deactivate')->name('deactive');

    Route::delete('/delete/{id}', 'Zpanel\PluginController@destroy')->name('destroy');
 
});

Route::group(['prefix' => 'themes', 'as' => 'theme.'], function () { 

	//Route::get('/{theme}/styles/{theme}/preview', 'Theme\ThemeController@show')->name('preview');

	Route::get('/preview', 'Theme\ThemeController@show')->name('preview');

	Route::get('/preview/{slug}', 'Theme\ThemeController@view')->name('view');

});


Route::group(['prefix' => 'merchant/account', 'middleware' => ['auth:admin', 'account.status'], 'as' => 'expire.'], function() {

    Route::get('/expired-trial', 'Zpanel\ExpireController@index')->name('trial.index');

    Route::get('/expired', 'Zpanel\ExpireController@expired')->name('plan');

    Route::get('/suspended', 'Zpanel\ExpireController@suspend')->name('suspend');

});

Route::group(['prefix' => 'merchant/account/store/plan', 'middleware' => ['auth:admin'], 'as' => 'plan.'], function() {

    Route::group(['middleware' => ['account.status']], function() {

        Route::get('/', 'Zpanel\PlanController@index')->name('index');

        Route::post('/', 'Zpanel\PlanController@store')->name('store');

    });
    
    Route::get('/renew', 'Zpanel\PlanController@show')->name('renew');

    Route::post('/{plan}/renew', 'Zpanel\PlanController@update')->name('update');

});

Route::group(['prefix' => 'merchant/admin/checkout', 'middleware' => ['auth:admin'], 'as' => 'store.'], function() {

    Route::get('/{billing}', 'Zpanel\CheckoutController@index')->name('checkout.index');

    Route::post('/{billing}', 'Zpanel\CheckoutController@update')->name('checkout.update');

    Route::post('/{billing}/place-order', 'Zpanel\CheckoutController@placeOrder')->name('checkout.place.order');

    Route::post('/{billing}/discount', 'Zpanel\CheckoutController@discount')->name('checkout.discount');

    Route::delete('/{billing}/discount', 'Zpanel\CheckoutController@destroy')->name('checkout.discount.destroy');

});

Route::group(['prefix' => 'merchant/admin/bills', 'middleware' => ['auth:admin']], function() {

    Route::get('/{billing}', 'Zpanel\BillingController@index')->name('bills.index');

});


Route::group(['middleware' => ['auth:admin']], function() {
    Route::post('/merchant/admin/response', 'Zpanel\CheckoutController@response')->name('checkout.response');
});
Route::post('/merchant/admin/client/response', 'Zpanel\CheckoutController@clientResponse');

