<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\StockTransferReason;

class StockTransferReasonSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $transfer_reasons = collect([['name' => 'Stock added'],
                                    ['name' => 'Stock decremented'],
                                    ['name' => 'Customer order'],
                                    ['name' => 'Product return'], 
                                    ['name' => 'Transfer to'],
                                  ]); 
        
        foreach($transfer_reasons as $reason) {
            StockTransferReason::create([ 
                'name' => $reason['name'],
            ]);
        }
    }
}
