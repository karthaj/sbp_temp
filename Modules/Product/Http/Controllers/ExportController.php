<?php

namespace Modules\Product\Http\Controllers;

use Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;

class ExportController extends Controller
{

    public function export()
    {
        $data = $this->getProductData();
        
        return Excel::create('products-'.date('Y-m-d'), function($excel) use($data) {

            $excel->sheet('products-'.date('Y-m-d'), function($sheet) use($data) {
    
                $sheet->fromArray($data);

            });

        })->export('csv');
    }

    protected function getProductData()
    {
		set_time_limit(100000);
		
        $data = [];
        $index = 0;
        $products = Product::with('variations')->where('state', 1)->get();


        for ($i=0; $i < $products->count(); $i++) { 
            $data[$index]['Product Type'] = $products[$i]->type;
            $data[$index]['Product ID'] = $products[$i]->id;
            $data[$index]['Product Name'] = $products[$i]->name;
            $data[$index]['Product Short Description'] = $products[$i]->short_description;
            $data[$index]['Product Long Description'] = $products[$i]->description;
            $data[$index]['Category'] = $this->getProductCategories($products[$i]);

            if($products[$i]->brand) {
                $data[$index]['Brand Name'] = $products[$i]->brand->name;
            } else {
                $data[$index]['Brand Name'] = null;
            }   
            
            $data[$index]['Product SKU'] = $products[$i]->sku;

            if($products[$i]->variations->count()) {
                $data[$index]['Variant Set'] = $products[$i]->optionSet->name;
            } else {
                $data[$index]['Variant Set'] = null;
            }

            $data[$index]['Cost Price'] = $products[$i]->cost_price;
            $data[$index]['Selling Price'] = $products[$i]->selling_price;
            $data[$index]['Stock'] = null;
            
            if($products[$i]->images->count()) {
                $data[$index]['Product Image'] = $this->getProductImages($products[$i]->images);
            } else {
                $data[$index]['Product Image'] = null;
            } 

            $data[$index]['Product Tags'] = $products[$i]->tags;
            $data[$index]['Product Barcode'] = $products[$i]->barcode;
            $data[$index]['Product ISBN'] = $products[$i]->isbn;
            $data[$index]['Product UPC'] = $products[$i]->upc;
            $data[$index]['Product Weight'] = $products[$i]->weight;
            $data[$index]['Product Width'] = $products[$i]->width;
            $data[$index]['Product Height'] = $products[$i]->height;
            $data[$index]['Product Depth'] = $products[$i]->depth;
            $data[$index]['Product Visible'] = $products[$i]->active ? 'Y' : 'N';
            $data[$index]['Product Url'] = $products[$i]->slug;
            $data[$index]['SEO Title'] = $products[$i]->meta_title;
            $data[$index]['SEO Description'] = $products[$i]->meta_description;
            $data[$index]['SEO Keywords'] = $products[$i]->meta_keywords;

            if($products[$i]->type == 'variant') {
                $attributes = $products[$i]->variations;

                foreach($attributes as $attribute) {
                    $data[$index+1]['Product Type'] = 'v-product';
                    $data[$index+1]['Product ID'] = $attribute->id;
                    $data[$index+1]['Product Name'] = $this->getCombinations($attribute->combinations);
                    $data[$index+1]['Product Short Description'] = null;
                    $data[$index+1]['Product Long Description'] = null;
                    $data[$index+1]['Category'] = null;
                    $data[$index+1]['Brand Name'] = null;
                    $data[$index+1]['Product SKU'] = $attribute->sku;
                    $data[$index+1]['Variant Set'] = null;
                    $data[$index+1]['Cost Price'] = $attribute->cost_price;
                    $data[$index+1]['Selling Price'] = $attribute->selling_price;
                    $data[$index+1]['Stock'] = null;
                    $data[$index+1]['Product Image'] = $attribute->image ? $attribute->image->large_default : null;
                    $data[$index+1]['Product Tags'] = null;
                    $data[$index+1]['Product Barcode'] = $attribute->barcode;
                    $data[$index+1]['Product ISBN'] = $attribute->isbn;
                    $data[$index+1]['Product UPC'] = $attribute->upc;
                    $data[$index+1]['Product Weight'] = $attribute->weight;
                    $data[$index+1]['Product Width'] = $attribute->width;
                    $data[$index+1]['Product Height'] = $attribute->height;
                    $data[$index+1]['Product Depth'] = $attribute->depth;
                    $data[$index+1]['Product Visible'] = null;
                    $data[$index+1]['Product Url'] = null;
                    $data[$index+1]['SEO Title'] = null;
                    $data[$index+1]['SEO Description'] = null;
                    $data[$index+1]['SEO Keywords'] = null;
                    $index += 2;
                }

            } else {
                $index += 1;
            }
            
        }

        return array_values($data);
    }

    protected function getProductImages($images)
    {
        $data = [];

        foreach ($images as $image) {
            array_push($data, $image->large_default);
        }

        return implode(';', $data);
    }

    protected function getCombinations($combinations)
    {
        $data = [];

        foreach($combinations as $combination) {
            
            if($combination->option->attribute->display_style === '[CS]') {
                array_push($data, $combination->option->attribute->display_style.$combination->option->attribute->public_name.'='.$combination->option->name.':'.$combination->option->color.$combination->option->pattern);
            } else {
                array_push($data, $combination->option->attribute->display_style.$combination->option->attribute->public_name.'='.$combination->option->name);
            }

        }

        return implode(',', $data);
    }

    protected function getProductCategories(Product $product)
    {
        $categories = [];
        if($product->categories->count()) {
            foreach($product->categories as $category) {

                if($category->has('parent')) {
                    if($category->is_root_category == 0 && !$category->parent->is_root_category) {
                        array_push($categories, $category->parent->name.'/'.$category->name.';');
                    } else {
                        array_push($categories, $category->name.';');
                    }
                    
                } else {
                    array_push($categories, $category->name.';');
                }
                
            }
        }
        
        
        return implode(' ', $categories);
    }
}
