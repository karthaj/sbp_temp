<?php

return [
    'products' => [
        'icon' => 'fa fa-tag',
        'title'=>'product',
        'parent' => '',
        'caret' => '<span class="arrow"></span>',
        'href'=> 'javascript:;',
        'order' => 3
    ],
    'all_products' => [
        'icon' => 'pg-tables',
        'title'=>'all products',
        'parent' => 'product',
        'caret' => '',
        'href'=> url('/merchant/product'),
        'order' => 1
    ],
    'categories' => [
        'icon' => 'pg-folder',
        'title'=>'categories',
        'parent' => 'product',
        'caret' => '',
        'href'=> url('/merchant/categories'),
        'order' => 2
    ],
    'product_options' => [
        'icon' => 'pg-folder',
        'title'=>'product variants',
        'parent' => 'product',
        'caret' => '',
        'href'=> url('/merchant/attributes'),
        'order' => 3
    ],
    'brands' => [
        'icon' => '',
        'title'=>'brands',
        'parent' => 'product',
        'caret' => '',
        'href'=> url('/merchant/brands'),
        'order' => 5
    ],
    'stocks' => [
        'icon' => '',
        'title'=>'stocks',
        'parent' => 'product',
        'caret' => '',
        'href'=> url('/merchant/product/stocks'),
        'order' => 6
    ],
    'tax' =>[
        'icon' => '',
        'title' => 'tax',
        'parent' => 'store',
        'caret' => '<span class="arrow"></span>',
        'href' => 'javascript:;',
        'order' => 6
    ],
    'tax_general' =>[
        'icon' => '',
        'title' => 'general',
        'parent' => 'tax',
        'caret' => '',
        'href' => url('/merchant/store/tax/general'),
        'order' => 1
    ],
    'tax_zone' =>[
        'icon' => '',
        'title' => 'tax zone',
        'parent' => 'tax',
        'caret' => '',
        'href' => url('/merchant/store/tax-zones'),
        'order' => 2
    ],
    'tax_class' =>[
        'icon' => '',
        'title' => 'tax class',
        'parent' => 'tax',
        'caret' => '',
        'href' => url('/merchant/store/tax-classes'),
        'order' => 3
    ],
    'tax_rates' =>[
        'icon' => '',
        'title' => 'tax rates',
        'parent' => 'tax',
        'caret' => '',
        'href' => url('/merchant/store/tax-rates'),
        'order' => 4
    ],
    'shipping' =>[
        'icon' => '',
        'title' => 'shipping',
        'parent' => 'store',
        'caret' => '',
        'href' => url('/merchant/store/shippings'),
        'order' => 4
    ],
    'shipping_class' =>[
        'icon' => '',
        'title' => 'shipping class',
        'parent' => 'store',
        'caret' => '',
        'href' => url('/merchant/store/shipping-classes'),
        'order' => 5
    ],
    'store_location' =>[
        'icon' => '',
        'title' => 'location',
        'parent' => 'store',
        'caret' => '',
        'href' => url('/merchant/store/locations'),
        'order' => 7
    ],
];
