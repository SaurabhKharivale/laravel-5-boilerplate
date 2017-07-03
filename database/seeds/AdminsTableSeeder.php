<?php

use App\Admin;
use App\Role;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::truncate();

        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        $super_admin_role = Role::where('name', 'super-admin')->first();
        $admin->assignRole($super_admin_role);

        $manager = factory(Admin::class)->create(['email' => 'manager@example.com']);
        $manager_role = Role::where('name', 'manager')->first();
        $manager->assignRole($manager_role);
    }
}
