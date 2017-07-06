<?php

namespace Tests\Feature\Backend\Permissions;

use App\Admin;
use App\Role;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;
use Tests\Support\Assertions\AdminAssertions;

class AssignRoleTest extends TestCase
{
    use AdminHelpers;

    /** @test */
    public function super_admin_can_assign_role_to_other_admin()
    {
        $super_admin = $this->createSuperAdmin('admin@example.com');
        $manager = factory(Admin::class)->create(['email' => 'manager@example.com']);
        $role = factory(Role::class)->create(['name' => 'manager']);
        $this->assertCount(0, $manager->roles);

        $response = $this->actingAs($super_admin, 'admin-api')
            ->json('POST', "/api/admin/{$manager->id}/role", [
                'role_id' => $role->id,
            ]);

        $response->assertStatus(200);
        $this->assertCount(1, $manager->fresh()->roles);
        $this->assertTrue($manager->fresh()->roles->contains('name', 'manager'));
    }

    /** @test */
    public function admin_with_assign_role_permission_can_assign_different_role_to_other_admin()
    {
        $role = $this->createRoleWithPermission('manager', 'assign-role');
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        $admin->assignRole($role);
        $another_admin = factory(Admin::class)->create();
        $executive_role = factory(Role::class)->create(['name' => 'executive']);
        $this->assertCount(0, $another_admin->roles);

        $response = $this->actingAs($admin, 'admin-api')
            ->json('POST', "/api/admin/{$another_admin->id}/role", [
                'role_id' => $executive_role->id,
            ]);

        $response->assertStatus(200);
        $this->assertCount(1, $another_admin->fresh()->roles);
        $this->assertTrue($another_admin->fresh()->roles->contains('name', 'executive'));
    }

    /** @test */
    public function admin_without_assign_role_permission_cannot_assign_role_to_other_admin()
    {
        $this->withExceptionHandling();
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        $another_admin = factory(Admin::class)->create();
        $executive_role = factory(Role::class)->create(['name' => 'executive']);
        $this->assertCount(0, $another_admin->roles);

        $response = $this->actingAs($admin, 'admin-api')
            ->json('POST', "/api/admin/{$another_admin->id}/role", [
                'role_id' => $executive_role->id,
            ]);

        $response->assertStatus(403);
        $this->assertCount(0, $another_admin->fresh()->roles);
        $this->assertFalse($another_admin->fresh()->roles->contains('name', 'executive'));
    }
}
