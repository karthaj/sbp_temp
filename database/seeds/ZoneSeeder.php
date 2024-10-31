<?php

use Illuminate\Database\Seeder;
use Shopbox\Models\Zpanel\Zone;
class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zones = collect([['name' => 'Europe', ],
                                ['name' => 'North America' ],
                                ['name' => 'Asia' ],
                                ['name' => 'Africa' ],
                                ['name' => 'Oceania' ],
                                ['name' => 'South America' ],
                                ['name' => 'Europe (non-EU)' ],
                                ['name' => 'Central America/Antilla' ],
                              ]); 
        
        foreach($zones as $zone) {
            Zone::create([ 
                'name' => $zone['name'],
            ]);
        }
    }
}
		