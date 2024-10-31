<?php

use Illuminate\Database\Seeder;
use Shopbox\Models\Zpanel\ImageDimension;
class ImageDimensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dimensions = collect([['name' => 'cart_default', 'width' => 125, 'height' => 125, 'products' => 1, 'categories' => 0, 'suppliers'                          => 0, 'stores' => 0, 'pattern' => 0],
                               ['name' => 'small_default', 'width' => 98, 'height' => 98 ,'products' => 1, 'categories' => 1, 'suppliers'   => 1, 'stores' => 0, 'pattern' => 0],
                               ['name' => 'medium_default', 'width' => 452, 'height' => 452, 'products' => 1, 'categories' => 0, 'suppliers' => 1, 'stores' => 0, 'pattern' => 0],
                               ['name' => 'home_default', 'width' => 250, 'height' => 250, 'products' => 1, 'categories' => 0, 'suppliers'   => 0, 'stores' => 0, 'pattern' => 0],
                               ['name' => 'large_default', 'width' => 800, 'height' => 800, 'products' => 1, 'categories' => 0, 'suppliers' => 1, 'stores' => 0, 'pattern' => 0],
                               ['name' => 'category_default', 'width' => 141, 'height' => 180, 'products' => 0, 'categories' => 1, 'suppliers' => 0, 'stores' => 1, 'pattern' => 0],
                               ['name' => 'store_default', 'width' => 170, 'height' => 115, 'products' => 0, 'categories' => 0, 'suppliers' => 0, 'stores' => 1, 'pattern' => 0],
                               ['name' => 'pattern_default', 'width' => 22, 'height' => 22, 'products' => 0, 'categories' => 0, 'suppliers' => 0, 'stores' => 0, 'pattern' => 1],
                              ]); 
        
        foreach($dimensions as $dimension) {
            ImageDimension::create([ 
                'name' => $dimension['name'],
                'width' => $dimension['width'],
                'height' => $dimension['height'],
                'products'=> $dimension['products'],
                'categories'=> $dimension['categories'],
                'suppliers'=> $dimension['suppliers'],
                'stores'=> $dimension['stores'],
                'pattern' => $dimension['pattern']
            ]);
        }
    }
}
