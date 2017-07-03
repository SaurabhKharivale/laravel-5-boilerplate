<?php

namespace Tests\Unit;

use App\Role;
use App\Permission;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    /** @test */
    public function can_get_all_roles_with_a_specific_permission()
    {
        $permission = factory(Permission::class)->create(['name' => 'view-revenue']);
        $manager = factory(Role::class)->create(['name' => 'manager']);
        $owner = factory(Role::class)->create(['name' => 'owner']);
        $seller = factory(Role::class)->create(['name' => 'seller']);
        $manager->grantPermission($permission);
        $owner->grantPermission($permission);

        $this->assertCount(2, $permission->roles);
        $this->assertTrue($permission->roles->contains($manager));
        $this->assertTrue($permission->roles->contains($owner));
        $this->assertFalse($permission->roles->contains($seller));
    }
}
