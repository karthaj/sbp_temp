<?php 


Route::group(['prefix' => 'merchant', 'middleware' => ['web', 'auth:admin', 'tenant', 'bindings'], 'namespace' => 'Modules\Product\Http\Controllers'], function()
{ 
    Route::group(['prefix' => 'product', 'as' => 'product.'], function () {

        Route::group(['middleware' => 'permission:view products'], function () {

            Route::resource('/datatable', 'DataTable\ProductController');

            Route::get('/', 'ProductController@index')->name('list');

        });

        Route::group(['middleware' => 'permission:add products'], function () {

            Route::get('/add', 'ProductController@create')->name('create.start');

            Route::get('/{product}/add', 'ProductController@create')->name('create');

            Route::patch('/{product}', 'ProductController@store')->name('store');

        });

        Route::group(['middleware' => 'permission:edit products'], function () {

            Route::get('/{product}/edit', 'ProductController@edit')->name('edit');

            Route::patch('/{product}/update', 'ProductController@update')->name('update');

        });

        Route::get('/{product}/duplicate', 'ProductController@duplicate')->name('duplicate');

        Route::get('/file/{file}', 'ProductController@downloadFile')->name('file.download');

        Route::delete('/files/{file}', 'ProductController@deleteProductFile')->name('file.delete');

        Route::patch('/variations/{product}/update', 'ProductController@updateVariations')->name('variations.update');

        Route::post('/variation/{product}/update', 'ProductController@updateVariation')->name('variation.update');

        Route::delete('/variation/{product}/{product_attribute}/delete', 'ProductController@destroyVariation');

        Route::delete('/variations/{product}/{attributes}/delete', 'ProductController@destroyVariations');

        Route::post('/variations/{product}', 'ProductController@attributesGeneratorAction');

        Route::delete('/variation/image/{product}/{product_attribute}', 'ProductController@deleteVariantImage');

        Route::get('/stocks', 'StockController@index')->name('stocks');

        Route::get('/stocks/new', 'StockController@create')->name('stocks.create');

        Route::get('/stock/{stock}/view', 'StockController@show')->name('stocks.view');

        Route::get('/import', 'ImportController@create')->name('import');

        Route::post('/import', 'ImportController@store')->name('import.store');

        Route::get('/export', 'ExportController@export')->name('export');

        Route::group(['middleware' => 'permission:bulk import export'], function () {

            Route::get('/bulk/editor', 'ProductController@bulkEditor')->name('bulk.editor');

        });
        
        Route::post('/bulk/image/upload', 'UploadController@bulkUpload')->name('bulk.image.upload');

        Route::post('/categories/create', 'ProductController@saveCategory')->name('category.save');

        Route::get('/categories/tree', 'ProductController@getCategories');

        Route::get('/categories/{product}/tree', 'ProductController@getProductCategories');

        Route::post('/brand/create', 'ProductController@saveBrand')->name('brand.save');

        Route::post('/taxclass/create', 'ProductController@saveTaxClass')->name('taxclass.save');

        Route::post('/shipping-class/create', 'ProductController@saveShippingClass')->name('shipping.class.save');

    });

    Route::group(['middleware' => 'permission:view variant sets'], function () {

        Route::resource('/datatable/variation-sets', 'DataTable\OptionSetController');

    });
    

    Route::get('/datatable/stocks', 'DataTable\StockController@index');

    Route::get('/warehouse/stocks', 'DataTable\WarehouseStockController@index');

    Route::get('/store/stocks', 'DataTable\StoreStockController@index');

    Route::get('/datatable/stock-history', 'DataTable\StockHistoryController@index');

    Route::post('warehouse/stocks/add', 'StockController@store')->name('warehouse.stocks.add');

    Route::group(['middleware' => 'permission:view stock transfers'], function () {

        Route::get('/datatable/stock/transfers', 'DataTable\StockTransferController@index');

        Route::get('/store/transfers', 'TransferController@index')->name('store.transfers');

        Route::get('/store/transfers/{transfer}', 'TransferController@show')->name('store.transfer.show');

        Route::get('/store/transfers/{transfer}/status', 'TransferController@viewStatus')->name('store.transfer.view');

    });

    Route::group(['middleware' => 'permission:add stock transfers'], function () {

        Route::get('/stock/transfer/create', 'StockController@createTransfer')->name('stock.transfer.create');

        Route::post('/stock/transfer/create', 'StockController@transfer')->name('stock.transfer');

    });

    Route::post('/store/transfers/{transfer}/status', 'TransferController@update')->name('store.transfer.update');

    Route::get('/stock/transfer/product/search', 'StockController@search')->name('transfer.product.search');

    Route::post('/stock/transfer/get-product', 'StockController@getProduct')->name('transfer.product.get');

    Route::group(['middleware' => 'permission:view stock requests'], function () {

        Route::get('/datatable/stock/requests', 'DataTable\StockRequestController@index');

        Route::get('/store/requests', 'RequestController@index')->name('store.requests');

    });

    Route::group(['middleware' => 'permission:add stock requests'], function () { 

        Route::get('/stock/request/create', 'StockRequestController@create')->name('stock.request.create');

        Route::post('/stock/request/create', 'StockRequestController@store')->name('stock.request');

    });

    Route::group(['middleware' => 'permission:view stock returns'], function () { 

        Route::get('/datatable/stock/returns', 'DataTable\StockReturnController@index');
        
        Route::get('/store/returns', 'StockReturnController@index')->name('store.returns');

    });
    
    Route::group(['middleware' => 'permission:add stock returns'], function () { 

        Route::get('/stock/return/create', 'StockReturnController@create')->name('stock.return.create');

        Route::post('/stock/return/create', 'StockReturnController@store')->name('stock.return');

        Route::post('/stock/return/product', 'StockReturnController@getProduct')->name('stock.return.product');

    });

    Route::group(['prefix' => 'upload', 'as' => 'upload.'], function () {

        Route::post('/{product}', 'UploadController@store')->name('store');

        Route::patch('/sort', 'UploadController@sort')->name('sort');

        Route::patch('/update/{product}/{product_image}', 'UploadController@update')->name('update');

        Route::delete('/{product}/{product_image}', 'UploadController@destroy')->name('destroy');
        
        Route::get('/update/{product}/{product_image}', 'UploadController@edit');

    });

    Route::group(['prefix' => 'brands', 'as' => 'brands.'], function () {

        Route::group(['middleware' => 'permission:view brands'], function () {

            Route::resource('/datatable', 'DataTable\BrandController');
        
            Route::get('/', 'BrandController@index')->name('index');

        });

        Route::group(['middleware' => 'permission:add brands'], function () {

            Route::get('/add', 'BrandController@create')->name('create');

            Route::post('/add', 'BrandController@store')->name('store');

        });

        Route::group(['middleware' => 'permission:edit brands'], function () {

            Route::get('/{brand}/edit', 'BrandController@edit')->name('edit');

            Route::patch('/{brand}/edit', 'BrandController@update')->name('update');

        });

        Route::delete('/{brand}/destroy', 'BrandController@destroy')->name('destroy');

    });

    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {

        Route::group(['middleware' => 'permission:view categories'], function () {

            Route::resource('/datatable', 'DataTable\CategoryController');

            Route::get('/', 'CategoryController@index')->name('index');

        });

        Route::group(['middleware' => 'permission:add categories'], function () {

            Route::get('/new', 'CategoryController@create')->name('new');

            Route::post('/new', 'CategoryController@store')->name('store');

        });

        Route::group(['middleware' => 'permission:edit categories'], function () {

            Route::get('/{category}', 'CategoryController@edit')->name('edit');

            Route::patch('/{category}', 'CategoryController@update')->name('update');

        });

        Route::post('/{category}/product/add', 'CategoryController@addProduct');

        Route::post('/{category}/product/remove', 'CategoryController@removeProduct');

    });

    Route::group(['prefix' => 'attributes', 'as' => 'attributes.'], function () {

        Route::group(['middleware' => 'permission:view variations'], function () {

            Route::resource('/datatable', 'DataTable\AttributeController');
   
            Route::get('/', 'AttributeController@index')->name('index');

        });

        Route::group(['middleware' => 'permission:add variations'], function () {

            Route::get('/new', 'AttributeController@create')->name('create');

            Route::post('/', 'AttributeController@store')->name('store');

        });

        Route::group(['middleware' => 'permission:edit variations'], function () {

            Route::get('/{attribute}/edit', 'AttributeController@edit');

            Route::post('/{attribute}', 'AttributeController@update')->name('update');

        });

        Route::group(['middleware' => 'permission:add variant sets'], function () {

            Route::get('/variation-sets/create', 'SetController@create')->name('sets.create');

            Route::post('/variation-sets/create', 'SetController@store')->name('sets.store');

        });

        Route::group(['middleware' => 'permission:edit variant sets'], function () {

            Route::get('/variation-sets/{option_set}/edit', 'SetController@edit')->name('sets.edit');

            Route::patch('/variation-sets/{option_set}/update', 'SetController@update')->name('sets.update');

        });

    });

    Route::group(['prefix' => 'features', 'as' => 'features.'], function () {

        Route::resource('/datatable', 'DataTable\FeatureController');

        Route::get('/', 'FeatureController@index')->name('index');

        Route::get('/add', 'FeatureController@create')->name('create');

        Route::post('/add', 'FeatureController@store')->name('store');

        Route::get('/{feature}/edit', 'FeatureController@edit')->name('edit');

        Route::patch('/{feature}', 'FeatureController@update')->name('update');

        Route::post('/order', 'FeatureController@sortOrder')->name('order');

    });

    Route::group(['prefix' => 'store', 'as' => 'tax.'], function () {

        Route::group(['middleware' => 'permission:view tax zones'], function () {

            Route::resource('/datatable/tax-zones', 'DataTable\\TaxZoneController');

            Route::get('/tax-zones', 'TaxZoneController@index')->name('zones.index');

        });

        Route::group(['middleware' => 'permission:add tax zones', 'prefix' => 'tax'], function () {

            Route::get('/tax-zone/add', 'TaxZoneController@create')->name('zone.create');

            Route::post('/tax-zone/add', 'TaxZoneController@store')->name('zone.store');

        });

        Route::group(['middleware' => 'permission:edit tax zones', 'prefix' => 'tax'], function () {

            Route::get('/tax-zone/{tax_zone}', 'TaxZoneController@edit')->name('zone.edit');

            Route::patch('/tax-zone/{tax_zone}', 'TaxZoneController@update')->name('zone.update');
        });

        Route::group(['middleware' => 'permission:view tax classes'], function () {

            Route::resource('/datatable/tax-classes', 'DataTable\TaxClassController');

            Route::get('/tax-classes', 'TaxController@index')->name('classes.index');

        });
        
        Route::group(['middleware' => 'permission:add tax classes'], function () {

            Route::post('/tax/tax-class/add', 'TaxController@store')->name('classes.store');

        });

        Route::group(['middleware' => 'permission:edit tax classes'], function () {

             Route::patch('/tax/tax-class/{tax_class}', 'TaxController@update');

        });

        Route::group(['middleware' => 'permission:view tax rates'], function () {

            Route::resource('/datatable/tax-rates', 'DataTable\\TaxController');

            Route::get('/tax-rates', 'TaxRateController@index')->name('rates.index');

        });


        Route::group(['middleware' => 'permission:add tax rates', 'prefix' => 'tax'], function () {

            Route::get('/tax-rate/add', 'TaxRateController@create')->name('rates.create');

            Route::post('/tax-rate/add', 'TaxRateController@store')->name('rates.store');

        });
        
        Route::group(['middleware' => 'permission:edit tax rates', 'prefix' => 'tax'], function () {

            Route::get('/tax-rate/{tax}', 'TaxRateController@edit')->name('rates.edit');

            Route::patch('/tax-rate/{tax}', 'TaxRateController@update')->name('rates.update');

        });
        

        Route::get('/tax/general', 'TaxController@edit')->name('edit');

        Route::patch('/tax/general/{tax_option}', 'TaxController@updateTaxOption')->name('update');


    });

    Route::group(['prefix' => 'store', 'as' => 'stores.'], function () {

        Route::group(['middleware' => 'permission:view locations'], function () {

            Route::resource('/datatable/store-locations', 'DataTable\StoreController');

            Route::get('/locations', 'StoreController@index')->name('index');

        });

        Route::group(['middleware' => 'permission:add locations'], function () {

            Route::get('/locations/add', 'StoreController@create')->name('create');

            Route::post('/locations/add', 'StoreController@store')->name('store');

        });

        Route::group(['middleware' => 'permission:edit locations'], function () {

            Route::get('/locations/{store_location}', 'StoreController@edit')->name('edit');

            Route::patch('/locations/{store_location}', 'StoreController@update')->name('update');

        });

        Route::patch('/location/{store_location}', 'StoreController@updateStatus')->name('update.status');

    });

    Route::group(['prefix' => 'store', 'as' => 'shipping.'], function () {

        /*Route::group(['middleware' => 'permission:view shipping classes'], function () {

            Route::resource('/datatable/shipping-classes', 'DataTable\ShippingClassController');

            Route::get('/shipping-classes', 'ShippingClassController@index')->name('classes.index');

        });*/

        Route::group(['middleware' => 'permission:add shipping classes'], function () {

            Route::get('/shipping-class/add', 'ShippingClassController@create')->name('classes.create');

            Route::post('/shipping-class/add', 'ShippingClassController@store')->name('classes.store');

        });

        Route::group(['middleware' => 'permission:edit shipping classes'], function () {

            Route::get('/shipping-class/{shipping_class}/edit', 'ShippingClassController@edit')->name('classes.edit');

            Route::patch('/shipping-class/{shipping_class}', 'ShippingClassController@update')->name('classes.update');

        });

        Route::group(['middleware' => 'permission:delete shipping classes'], function () {

            Route::delete('/shipping-class/{shipping_class}/delete', 'ShippingClassController@destroy')->name('classes.delete');

        });


        Route::group(['middleware' => 'permission:view shipping zones'], function () {

            //Route::resource('/datatable/shippings', 'DataTable\ShippingZoneController');

            Route::get('/shippings', 'ShippingController@index')->name('index');

        });

        Route::post('/config/storepickup', 'ShippingController@configStorePickup')->name('config.storepickup');

        Route::group(['middleware' => 'permission:add shipping zones'], function () {

            Route::get('/shippings/zone/add', 'ShippingController@create')->name('zones.create');

            Route::post('/shippings/zone/add', 'ShippingController@store')->name('zones.store');

        });

        Route::group(['middleware' => 'permission:edit shipping zones'], function () {

            Route::get('/shipping/{shipping_zone}', 'ShippingController@edit')->name('edit');

            Route::patch('/shipping/{shipping_zone}', 'ShippingController@update')->name('update');

        });

        Route::group(['middleware' => 'permission:delete shipping zones'], function () {

            Route::delete('/shipping/{shipping_zone}/delete', 'ShippingController@destroy')->name('zone.delete');

        });

        //Route::get('/shipping/zone/configure/{shipping_zone}', 'ShippingZoneMethodController@edit')->name('options.edit');

        Route::patch('/shipping/zone/configure/{shipping_zone_method}', 'ShippingZoneMethodController@update')->name('options.update');

        //Route::patch('/shipping/zone/configure/store-pickup/{shipping_zone_method}', 'ShippingZoneMethodController@updateStorePickupStatus')->name('options.update');

        Route::patch('/shippings/free-shipping/{shipping_zone_method}', 'ShippingZoneMethodController@updateFreeShipping')->name('free_shipping.update');

        Route::patch('/shippings/flat-rate/{shipping_zone_method}', 'ShippingZoneMethodController@updateFlatRate')->name('flat_rate.update');

        Route::patch('/shippings/ship-weight-order/{shipping_zone_method}', 'ShippingZoneMethodController@updateDeliveryRate')->name('delivery.rates.update');

        //Route::patch('/shippings/store-pickup/{shipping_zone_method}', 'ShippingZoneMethodController@updateStorePickup')->name('store_pickup.update');

    });

    Route::get('/countries/states', 'CountryController@getStates');

    Route::get('/country/states', 'CountryController@getCountryStates');

    Route::get('/country/cities', 'CountryController@getCountryCities');
    
    Route::get('/country/checkzipcode', 'CountryController@checkZipCode');


});