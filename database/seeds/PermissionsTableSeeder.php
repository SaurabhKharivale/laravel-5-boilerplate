<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();

        Permission::create(['name' => 'create-admin', 'label' => 'Create new admin account']);
        Permission::create(['name' => 'view-revenue', 'label' => 'View revenue report']);
        Permission::create(['name' => 'ban-user', 'label' => 'Ban user account']);
    }
}
