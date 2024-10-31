<?php

/*
Route::post('/products', 'ProductController@store')->name('products.store');
Route::get('/products/{product}', 'ProductController@show')->name('products.show');

Route::get('/{store}', 'DashboardController@index');*/

Route::group(['prefix' => 'merchant'], function () {

    Route::get('/dashboard', 'Zpanel\DashboardController@index')->name('dashboard');

    Route::get('/stores/new', 'Zpanel\Store\IndexController@create')->name('merchant.stores.create');

    Route::post('/stores/new', 'Zpanel\Store\IndexController@store')->name('merchant.stores.store');

    Route::get('/apps', 'Zpanel\AppController@index')->name('apps');

    Route::post('/permalink', 'Zpanel\PermalinkController@index');

    Route::get('/products/dropdown.json', 'Zpanel\Dropdown\ProductController@index');

    Route::get('/products/{product}', 'Zpanel\Dropdown\ProductController@show');

    Route::get('/brands/dropdown.json', 'Zpanel\Dropdown\BrandController@index');

    Route::get('/brands/{brand}', 'Zpanel\Dropdown\BrandController@show');

    Route::get('/linklists/dropdown.json', 'Zpanel\Dropdown\MenuController@index');

    Route::get('/linklists/{menu}', 'Zpanel\Dropdown\MenuController@show');

    Route::get('/collections/dropdown.json', 'Zpanel\Dropdown\CategoryController@index');

    Route::get('/collections/{category}', 'Zpanel\Dropdown\CategoryController@show');

    Route::get('/fonts/dropdown.json', 'Zpanel\Dropdown\FontController@index');

    Route::get('/fonts/{font_variation}/dropdown.json', 'Zpanel\Dropdown\FontController@show');

});


Route::group(['prefix' => 'merchant/account', 'as' => 'account.'], function() {

    Route::group(['middleware' => 'permission:view users'], function() {

        Route::get('/datatable/users', 'Zpanel\DataTable\AccountController@index');

        Route::get('/users', 'Zpanel\Account\UserController@index')->name('users');

    });

    Route::group(['middleware' => 'permission:view users', 'prefix' => 'users'], function() {

        Route::get('/new', 'Zpanel\Account\AccountController@create')->name('users.create');

        Route::post('/new', 'Zpanel\Account\AccountController@store')->name('users.store');

    });
    
    Route::group(['middleware' => 'permission:edit users', 'prefix' => 'users'], function() {

        Route::get('/{user}/edit', 'Zpanel\Account\AccountController@edit');

        Route::patch('/{user}', 'Zpanel\Account\AccountController@update')->name('users.update');

    });

    Route::post('/{user}/resend/email', 'Zpanel\Account\AccountController@resendEmail')->name('users.email.resend');

    Route::group(['middleware' => 'permission:delete users'], function() {

        Route::delete('/datatable/users/{ids}', 'Zpanel\DataTable\AccountController@destroy');

    });

    Route::group(['middleware' => 'permission:edit users'], function() {

        Route::patch('/datatable/users/{id}', 'Zpanel\DataTable\AccountController@update');

    });

    Route::get('/profile', 'Zpanel\Account\ProfileController@index')->name('profile.index');

    Route::post('/profile', 'Zpanel\Account\ProfileController@store')->name('profile.store');

});


Route::group(['prefix' => 'store'], function() {

    Route::group(['middleware' => 'permission:view payments'], function() {

        Route::get('/payments', 'Zpanel\Payment\PaymentController@index')->name('payments.index');

    });

    Route::patch('/payments/{plugin}', 'Zpanel\Payment\PaymentController@update')->name('payments.update');

});

Route::group(['prefix' => 'merchant/settings', 'as' => 'settings.'], function() {

    Route::get('/', 'Zpanel\Setting\StoreController@index')->name('index');

    Route::group(['middleware' => 'permission:view general settings'], function() {

        Route::get('/general', 'Zpanel\Setting\StoreController@edit')->name('edit');

    });

    Route::group(['middleware' => 'permission:edit general settings'], function() {

        Route::patch('/general', 'Zpanel\Setting\StoreController@update')->name('update');

    });

    Route::group(['prefix' => 'billing'], function() {

        Route::get('/', 'Zpanel\Setting\BillingController@index')->name('billing.index');

        Route::post('/service/cancel', 'Zpanel\Setting\BillingController@update');

    });

    Route::group(['middleware' => 'permission:custom domain', 'as' => 'domain.'], function() {

        Route::get('/domain/connect', 'Zpanel\Setting\DomainController@index')->name('index');

        Route::post('/domain/connect', 'Zpanel\Setting\DomainController@create')->name('create');

        Route::get('/domain/verify', 'Zpanel\Setting\DomainController@show')->name('verify');

        Route::post('/domain/add', 'Zpanel\Setting\DomainController@store')->name('store');

        Route::post('/domain/ssl/generate', 'Zpanel\Setting\DomainController@update')->name('update');

    });

});

Route::group(['prefix' => 'merchant/store/preferences', 'as' => 'store.preferences.'], function() {

    Route::get('/', 'Zpanel\PreferenceController@index')->name('index');

    Route::patch('/{store}', 'Zpanel\PreferenceController@update')->name('update');

});

Route::get('/auth/shopbox/refresh', 'ShopboxAuthController@refresh');

Route::group(['prefix' => 'theme/{theme_id}/', 'as' => 'theme.'], function() {

    Route::get('/editor', 'Zpanel\Theme\ThemeController@editor')->name('editor');

    // Route::post('/reorder/sections', 'Zpanel\Theme\ThemeController@reorder')->name('sections.reorder');

    Route::post('/update_current', 'Zpanel\Theme\ThemeController@store')->name('config.store');

    Route::post('/reset_default', 'Zpanel\Theme\ThemeController@reset')->name('config.reset');

    Route::post('/customize', 'Zpanel\Theme\ThemeController@customize')->name('customize');

    Route::post('/override', 'Zpanel\Theme\ThemeController@override')->name('override');

    Route::post('/uploads', 'Zpanel\ImageController@store')->name('uploads');

    //Route::post('/add-section', 'Zpanel\Theme\ThemeController@addSection')->name('add.section');

    //Route::post('/remove-section', 'Zpanel\Theme\ThemeController@removeSection')->name('remove.section');

});

Route::post('/merchant/themes/{theme_id}/active', 'Zpanel\Theme\ThemeController@activeTheme');

Route::group(['middleware' => 'permission:view themes'], function() {

    Route::get('/store-front/my-themes', 'Zpanel\Theme\ThemeController@index')->name('theme.index');
    
});

Route::get('/store-front/my-themes/{store_theme}/update', 'Zpanel\Theme\ThemeController@update')->name('theme.update');

Route::group(['prefix' => 'merchant/pos', 'as' => 'pos.'], function() {

    Route::get('/', 'Zpanel\POS\PosController@index')->name('index');

    Route::group(['middleware' => 'permission:access pos'], function() {

        Route::get('/config', 'Zpanel\POS\PosController@config')->name('config');
    
    });

    Route::get('/products', 'Zpanel\POS\PosController@products')->name('get.products');

    Route::get('/categories', 'Zpanel\POS\PosController@categories')->name('get.categories');

    Route::post('/cart/add', 'Zpanel\POS\CartController@store');

});

Route::group(['prefix' => 'merchant', 'as' => 'plan.', 'middleware' => 'plan.switch'], function() {

    Route::get('/store/change/plan', 'Zpanel\Account\PlanController@index')->name('change.index');

    Route::get('/store/change/plan/{plan}', 'Zpanel\Account\PlanController@show')->name('change.show')->middleware('has.billing.address');

    Route::post('/store/change/plan/{plan}', 'Zpanel\Account\PlanController@store')->name('change.store')->middleware('has.billing.address');
    
});

Route::group(['prefix' => 'merchant'], function() {

    Route::post('/image/upload', 'Zpanel\UploadController@store');

    Route::get('/images.json', 'Zpanel\UploadController@show');

    Route::get('/country/{country}/states', 'Zpanel\Setting\StoreController@getCountryStates');

    Route::get('/payouts.json', 'Zpanel\Payout\PayoutController@payouts');

    Route::get('/payouts', 'Zpanel\Payout\PayoutController@index')->name("payouts.index");

    Route::get('/store/bills', 'Zpanel\Setting\BillingController@getBills');

    Route::get('/store/bills/download/{billing}', 'Zpanel\Setting\BillingController@downloadBill')->name('invoice.download');

});


Route::group(['prefix' => 'marketplace'], function() {

    Route::get('/themes', 'Zpanel\Marketplace\ThemeController@index')->name('marketplace.themes');

    Route::get('/themes/{theme}', 'Zpanel\Marketplace\ThemeController@show')->name('theme.show')->middleware('has.billing.address');

    Route::post('/purchase-theme', 'Zpanel\Marketplace\ThemeController@store')->name('theme.purchase');

    Route::get('/plugins', 'Zpanel\Marketplace\PluginController@index')->name('marketplace.plugins');

    Route::post('/plugins', 'Zpanel\Marketplace\PluginController@refine');

    Route::get('/plugins/{plugin}', 'Zpanel\Marketplace\PluginController@show')->name('plugin.show')->middleware('has.billing.address');

    Route::get('/plugins/browse/{plugin_category}', 'Zpanel\Marketplace\PluginController@update');

    Route::post('/purchase-plugin', 'Zpanel\Marketplace\PluginController@store')->name('plugin.purchase');

});

Route::group(['prefix' => 'merchant/analytics'], function() {

    Route::get('/', 'Zpanel\Analytics\AnalyticsController@index')->name('analytics.index');

    Route::post('/store_visits', 'Zpanel\Analytics\VisitController@index');

    Route::post('/orders', 'Zpanel\Analytics\OrderController@index');

    Route::post('/sales', 'Zpanel\Analytics\OrderController@sales');

});
