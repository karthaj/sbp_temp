<?php

use Illuminate\Database\Seeder;
use Shopbox\Models\Zpanel\Permission;
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        $permissions = collect([['parent_id' => null, 'name' => 'settings', 'status' => 1],
                                ['parent_id' => 1, 'name' => 'add staff', 'status' => 1],
                                ['parent_id' => 1, 'name' => 'view staff', 'status' => 1],
                                ['parent_id' => 1, 'name' => 'delete staff', 'status' => 1]
                              ]); 
        
        foreach($permissions as $permission) {
            Permission::create([ 
                'parent_id' => $permission['parent_id'],
                'name' => $permission['name'],
                'status'=> $permission['status']
            ]);
        }
        
    }
}
