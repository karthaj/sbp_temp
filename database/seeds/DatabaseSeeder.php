<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(PermissionSeeder::class);
        $this->call(ImageDimensionSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(ZoneSeeder::class);
        $this->call(WeightSeeder::class);
        $this->call(IndustrySeeder::class);
    }
}
