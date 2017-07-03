<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();

        Role::create(['name' => 'super-admin', 'label' => 'Super Admin']);
        Role::create(['name' => 'manager', 'label' => 'Manager']);
        Role::create(['name' => 'executive', 'label' => 'Executive']);
    }
}
