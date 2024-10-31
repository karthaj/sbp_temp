<?php

namespace Modules\Product\Traits;

use Shopbox\Models\Zpanel\Store;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\TaxClass;
use Modules\Product\Entities\Product;

trait ValidateImportTrait
{
	protected function saveBrand($value, Store $store)
    {
        $brand = $store->brands()->where('name', $value)->first();

        if(!$brand) {
            $brand = new Brand;
            $brand->name = $value;
            $brand->slug = str_slug($value,'-');
            $brand->meta_title = $value;
            $brand->save();

            return $brand;
        }

        return $brand;
    }

    protected function getCategories($categories, Store $store) {
        $categories = explode(';', $categories);
        $categories = array_filter($categories, function($value) { return trim($value) != ''; });
        $data = [];
        $prev = '';

        foreach ($categories as $key => $category) {
            if(str_contains($category, '/')){
                $sub_category = explode('/', $category);
                for ($i=0; $i < count($sub_category); $i++) { 

                    if($prev != '') {
                        $prev = $this->saveCategory(trim($sub_category[$i]), $store, $prev);
                    } else {
                        $prev = $this->saveCategory(trim($sub_category[$i]), $store);
                    }
                        
                    array_push($data, $prev);
                }
                   

            } else {
                $result = $this->getCategory($store, trim($category));
                if($result) { 
                    array_push($data, $result);
                } else {
                    array_push($data, $this->saveCategory(trim($category), $store));
                }
            } 
        }
      
        return $data;
    }

    protected function getCategory(Store $store, $category)
    {
        $category = $store->categories()->where('name', $category)->first();

        if(!empty($category)) {
       
            return $category->id;
        }

        return false;
    }

    protected function checkCategoryExists(Store $store, $category) {

        $category = $store->categories()->where('name', $category)->first();

        if(!empty($category)) {
       
            return $category->id;
        }

        return false;

    }

    protected function saveCategory($value, Store $store, $parent_category = null)
    {
        $parent = $store->categories()->where('is_root_category', 1)->first()->id;
        if(!empty($parent_category)) {
            $parent = $parent_category;
        } 

        $data = $this->checkCategoryExists($store, $value);

        if($data) {
            return $data;
        }

        $category = new Category;
        $category->parent_id = $parent;
        $category->name = $value;
        $category->slug = str_slug($value, '-');
        $category->status = 1;
        $category->save();

        return $category->id;
    }

    // protected function getTaxClass($value, $store)
    // {
    //     $result = TaxClass::where('name', $value)->first();

    //     if(!$result) {
    //         $tax_class = new TaxClass;
    //         $tax_class->name = $value;
    //         $tax_class->save();

    //         return $tax_class;
    //     }
      
    //     return $result;
    // }

    protected function setDefaultTaxClass(Store $store)
    {
        return $store->taxClasses()->where('name', 'Default Tax Class')->first();
    }

    protected function validateString($value, $product, $attribute, $message)
    {
        $result = is_string($value);

        if(!$result) {
            $this->errors[$product][$attribute] = $message;
        }
    }

    protected function validateProductCondition($value)
    {
        switch(strtolower($value))
        {
            case 'new':
            return true;
            break;

            case 'used':
            return true;
            break;

            case 'refurbished':
            return true;
            break;

            default:
            return false;
        }
    }

    protected function validateProductType($value)
    {
        switch(strtolower($value))
        {
            case 'standard':
            return true;
            break;

            case 'variant':
            return true;
            break;

            case 'virtual':
            return true;
            break;
            
            default:
            return false;
        }
    }

    protected function validNumeric($value)
    {
        if(is_numeric($value)) {

            return true;

        }

        return false;
    }

    protected function validatePrice($value, $product)
    {
        if(empty($value)) {
            return false;
        }

        if(is_numeric($value) && $value >= 0) {

            return true;

        }
        $this->errors[$product]['cost_price'] = 'Cost Price must be a valid non-negative number.';
        return false;
    }

    protected function validateDate($value, $product, $attribute)
    {
        if(empty($value)) {
            return false;
        }

        $date = date_parse($value);

        $result = checkdate($date['month'], $date['day'], $date['year']);

        if(!$result) {
        	$this->errors[$product][$attribute] = 'Invalid date format.';
            return false;
        }

        return true;
    }

    protected function validateDateTime($value, $product, $attribute)
    {
        if(empty($value)) {
            return false;
        }

        $value = strtotime($value);

        if (! is_numeric($value)) {
            $this->errors[$product][$attribute] = 'Invalid datetime format.';
            return false;
        }

        return true;
    }

    protected function getFormattedDateTime($value) 
    {
        $value = strtotime($value);
    
        return date('Y-m-d H:i:s',$value);
    }

    protected function getFormattedDate($value) 
    { 
    	$date = strtotime($value);
    
        return date_format(date_create($date), 'Y-m-d');
    }


    protected function validateProductIdentifier($value, $identifier, $message, $product_name, $attribute, $product)
    {
        if(!empty($value)) {
            $result = (bool) session('store')->products()->where($identifier, $value)->where('id', '<>', $product->id)->count();

            if($result) {
                $this->errors[$product_name][$attribute] = $message;
                return false;
            }

            return true;
        }

        return false;
       
    }
}