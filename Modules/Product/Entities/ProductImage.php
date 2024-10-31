<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use ForTenants;

    protected $fillable = [
        'cart_default',
        'home_default',
        'small_default',
        'medium_default',
        'large_default',
        'alt_text',
        'cover',
        'sort-order',
    ];

    public function product() 
    {
        return $this->belongsTo(Product::class);
    }

    public function productAttribute() 
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function store() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    /**
     *Get product cover
     *
     * @param int $idProduct Product ID
     *
     * @return bool result
     */
    public static function getCover($product_id)
    {  
        return (int) ProductImage::where('cover', 1)->where('product_id', $product_id)
                ->where('store_id', session('tenant'))->count();
    }

    /**
     * Return highest position of images for a product
     *
     * @param int $idProduct Product ID
     *
     * @return int highest position of images
     */
    public static function getHighestPosition($product_id)
    {  
        return (int) ProductImage::where('product_id',$product_id)->count();
    
    }

    /**
     * Delete product cover
     *
     * @param int $idProduct Product ID
     *
     * @return bool result
     */
    public static function deleteCover(Product $product)
    {
        foreach ($product->images as $image) {
            $image->cover = 0;
            $image->save();
        }
    
        return 1;
    }
}
