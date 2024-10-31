<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ShippingMethod;

class ShippingMethodSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $shipping_methods = collect([['name' => 'Free Shipping', 
                                    'description' => 'Use free shipping to improve checkout conversion, increase average order value and reduce abandoned carts.'],
                                    ['name' => 'Flat Rate', 'description' => 'Lets you charge a fixed rate for shipping.'],
                                    ['name' => 'Ship by weight / order total', 
                                    'description' => 'Calculate shipping cost based on order value or the total weight of items.' ],
                                    ['name' => 'Store Pickup', 'description' => 'Your customers can pickup / collect their orders from your store’s physical retail location.' ],
                                  ]); 
        
        foreach($shipping_methods as $shipping_method) {
            ShippingMethod::create([ 
                'name' => $shipping_method['name'],
                'description' => $shipping_method['description']
            ]);
        }
    }
}
