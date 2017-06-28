<?php

namespace Tests\Feature\Authorization;

use App\Role;
use App\Admin;
use Tests\TestCase;

class RolesCanBeAssignedToAdminTest extends TestCase
{
    /** @test */
    public function a_role_can_be_assigned_to_admin()
    {
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        $this->assertCount(0, $admin->fresh()->roles);

        $admin->assignRole($manager_role);

        $this->assertCount(1, $admin->fresh()->roles);
        $this->assertTrue($admin->fresh()->roles->contains('name', 'manager'));
    }

    /** @test */
    public function can_be_assign_multiple_roles_to_admin()
    {
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $owner_role = factory(Role::class)->create(['name' => 'owner']);
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        $this->assertCount(0, $admin->fresh()->roles);

        $admin->assignRole([$manager_role, $owner_role]);

        $this->assertCount(2, $admin->fresh()->roles);
        $this->assertTrue($admin->fresh()->roles->contains('name', 'manager'));
        $this->assertTrue($admin->fresh()->roles->contains('name', 'owner'));
    }
}
