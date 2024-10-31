<?php

namespace Modules\Product\Traits;

use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\ProductAttribute;

trait HasVariation {

	public function getCombinationAvailability($product_id, $option_id) {
		
		$disabled = false;

		$combinations = DB::table('product_attribute_combinations')
                            ->select('product_attribute_id')
                            ->join('product_attributes', 'product_attributes.id', '=', 'product_attribute_combinations.product_attribute_id')
                            ->where('product_id', $product_id)
                            ->where('option_id', $option_id)
                            ->get();

              
    foreach($combinations as $combination) {
    	$product_attribute = ProductAttribute::find($combination->product_attribute_id);
    	$stock = $product_attribute->stock->storeStocks()->where('store_location_id', session('store')->onlineStore->id)->first();

    	if($stock && $stock->quantity) {

        $disabled = false;
          break;
   			
   		} else {

   			$disabled = true;
        
   		}

    }
  
    return $disabled;
        
	}

}