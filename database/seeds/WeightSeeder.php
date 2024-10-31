<?php

use Illuminate\Database\Seeder;
use Shopbox\Models\Zpanel\WeightUnit;
class WeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $weights = collect([['weight' => 'kilogram', 'weight_code' => 'kg', 'status' => 1],
                                ['weight' => 'gram', 'weight_code' => 'g', 'status' => 1],
                                ['weight' => 'pounds', 'weight_code' => 'lb', 'status' => 1],
                                ['weight' => 'ounces', 'weight_code' => 'oz', 'status' => 1],
                                ['weight' => 'tonnes', 'weight_code' => 't', 'status' => 1],
                              ]); 
        
        foreach($weights as $weight) {
            WeightUnit::create([ 
                'weight' => $weight['weight'],
                'weight_code' => $weight['weight_code'],
                'status'=> $weight['status']
            ]);
        }
    }
}
