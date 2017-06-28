<?php

namespace Tests\Feature\Authorization;

use App\Role;
use App\Permission;
use Tests\TestCase;

class PremissionsCanBeGrantedToRoleTest extends TestCase
{
    /** @test */
    public function can_grant_permission_to_a_role()
    {
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $permission = factory(Permission::class)->create(['name' => 'view-revenue']);
        $this->assertCount(0, $manager_role->fresh()->permissions);

        $manager_role->grantPermission($permission);

        $this->assertCount(1, $manager_role->fresh()->permissions);
        $this->assertTrue($manager_role->fresh()->permissions->contains('name', 'view-revenue'));
    }

    /** @test */
    public function can_grant_multiple_permission_to_a_role()
    {
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $permission_one = factory(Permission::class)->create(['name' => 'view-revenue']);
        $permission_two = factory(Permission::class)->create(['name' => 'generate-report']);
        $this->assertCount(0, $manager_role->fresh()->permissions);

        $manager_role->grantPermission([$permission_one, $permission_two]);

        $this->assertCount(2, $manager_role->fresh()->permissions);
        $this->assertTrue($manager_role->fresh()->permissions->contains('name', 'view-revenue'));
    }
}
