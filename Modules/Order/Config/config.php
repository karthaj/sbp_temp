<?php

return [
	'orders'=>[  
	    'icon' => 'pg-download',
	    'title'=>'orders',
	    'parent' => '',
	    'caret' => '<span class="arrow"></span>',
	    'href'=> 'javascript:;',
	    'order' => 2
	],
	'orders_view'=>[  
	    'icon' => '',
	    'title'=>'view',
	    'parent' => 'orders',
	    'caret' => '',
	    'href'=> url('merchant/orders'),
	    'order' => 1
	],
];