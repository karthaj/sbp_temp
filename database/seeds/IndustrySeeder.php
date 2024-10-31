<?php

use Illuminate\Database\Seeder;
use Shopbox\Models\Front\Industry;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $industries = collect([['name' => 'all', 'slug' => str_slug('all', '-')],
                                ['name' => 'art & photography', 'slug' => str_slug('art & photography', '-')],
                                ['name' => 'fashion & jewelry', 'slug' => str_slug('fashion & jewelry', '-')],
                                ['name' => 'electronics', 'slug' => str_slug('electronics', '-')],
                                ['name' => 'food & drink', 'slug' => str_slug('food & drink', '-')],
                                ['name' => 'home & garden', 'slug' => str_slug('home & garden', '-')],
                                ['name' => 'furniture', 'slug' => str_slug('furniture', '-')],
                                ['name' => 'health & beauty', 'slug' => str_slug('health & beauty', '-')],
                                ['name' => 'sports & recreation', 'slug' => str_slug('sports & recreation', '-')],
                                ['name' => 'toys & games', 'slug' => str_slug('toys & games', '-')],
                                ['name' => 'animals & pets', 'slug' => str_slug('animals & pets', '-')],
                              ]); 
        
        foreach($industries as $industry) {
            Industry::create([ 
                'name' => $industry['name'],
                'slug' => $industry['slug']
            ]);
        }
    }
}
