<?php

namespace Modules\Product\Traits;

trait HasPrice {

	public function getFormattedPrice($price) 
    {
        return session('store')->setting->currency->iso_code.' '.number_format($price,2);
    }

}