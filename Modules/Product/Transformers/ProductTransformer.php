<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Traits\HasPrice;
use Modules\Product\Traits\HasVariation;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Combination;


class ProductTransformer extends TransformerAbstract
{
	use HasVariation, HasPrice;

	public function transform($product)
	{
		
		return [
			'id' => $product->id,
			'type' => $product->type,
			'name' => $product->name,
			'description' => $product->description,
			'short_description' => $product->short_description,
			'price' => $product->selling_price,
			'special_price' => $product->special_price ?: 0,
			'sku' => $product->sku,
			'barcode' => $product->barcode,
			'isbn' => $product->isbn,
			'upc' => $product->upc,
			'in_stock' => $this->productInStock($product) > 0 ? true : false,
			'stock_count' =>  $this->productInStock($product),
			'instock_label' => $product->available_now,
			'outofstock_label' => $product->available_later,
			'images' => $product->getImages(),
			'categories' => $product->getCategories(),
			'related_products' => $this->getRelatedProducts($product),
			'variants' => $this->getProductVariants($product),
			'url' => getStoreUrl($product->store).'/products/'.$product->slug
		];
	}

	protected function productInStock(Product $product) {

		if(!$product->variations->count()) {

			$stock = 0;

			if($product->stock && session('store')->onlineStore) {

				$stock = $product->stock->storeStocks()->where('store_location_id', session('store')->onlineStore->id)->first();

			}
			

			if($stock) {

	    		return $stock->quantity;

	   		} 

   			return 0;
		}
		 
   		return 0;
	}

    public function getRelatedProducts(Product $product) {

        $data = [];

        if($product->relatedProducts->count()) {
            foreach($product->relatedProducts as $key => $product) {
                $data[$key]['id'] = $product->id;
                $data[$key]['type'] = $product->type;
                $data[$key]['name'] = $product->name;
                $data[$key]['url'] = route('stores.product.show', $product);
                $data[$key]['price'] = $this->getFormattedPrice($product->selling_price);
                $data[$key]['special_price'] = $this->getFormattedPrice($product->special_price);
                $data[$key]['sku'] = $product->sku;
                $data[$key]['barcode'] = $product->barcode;
                $data[$key]['isbn'] = $product->isbn;
                $data[$key]['upc'] = $product->upc;
                $data[$key]['images'] = $product->getImages($product);
            }
            return $data;
        }
        
        return $data;
        
    }

	protected function getProductVariants(Product $product) {
		$variants = [];
	
		if($product->variations->count()) {
			foreach($product->variations as $variation) {
	            foreach($variation->combinations as $key => $combination) {
	                $variants[$combination->option->attribute->display_style][$combination->option->attribute->public_name][$combination->option->name]['id'] = $combination->option->id;
	                $variants[$combination->option->attribute->display_style][$combination->option->attribute->public_name][$combination->option->name]['attribute_id'] = $combination->option->attribute_id;
	                $variants[$combination->option->attribute->display_style][$combination->option->attribute->public_name][$combination->option->name]['name'] = $combination->option->name;
	                $variants[$combination->option->attribute->display_style][$combination->option->attribute->public_name][$combination->option->name]['active'] = false;
	                $variants[$combination->option->attribute->display_style][$combination->option->attribute->public_name][$combination->option->name]['disabled'] = $this->attributeDisabled($combination, $product->id);
	                $variants[$combination->option->attribute->display_style][$combination->option->attribute->public_name][$combination->option->name]['color'] = $combination->option->color;
	                $variants[$combination->option->attribute->display_style][$combination->option->attribute->public_name][$combination->option->name]['pattern'] = $combination->option->pattern;
	            }
	            
	        }
		}
      
        return $variants;
	}

	protected function attributeDisabled (Combination $combination, $product_id) {
	  
		return $this->getCombinationAvailability($product_id, $combination->option_id);
	
	}
	
}