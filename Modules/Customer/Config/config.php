<?php

return [
    'customer' => [
        'icon' => 'fa fa-users',
        'title'=>'customers',
        'parent' => '',
        'caret' => '<span class="arrow"></span>',
        'href'=> 'javascript:;',
        'order' => 4
    ],
    'customer_view' => [
        'icon' => '',
        'title'=>'view',
        'parent' => 'customers',
        'caret' => '',
        'href'=> url('merchant/customers'),
        'order' => 1
    ],
    'customer_group' => [
        'icon' => '',
        'title'=>'customer groups',
        'parent' => 'customers',
        'caret' => '',
        'href'=> url('merchant/customers/groups'),
        'order' => 3
    ],
];

