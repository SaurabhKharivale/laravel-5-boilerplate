<?php

namespace Tests\Feature\Backend\Permissions;

use App\Role;
use App\Admin;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;
use Tests\Support\Assertions\AdminAssertions;

class AssignRoleTest extends TestCase
{
    use AdminHelpers, AdminAssertions;

    /** @test */
    public function super_admin_can_assign_role_to_other_admin()
    {
        $super_admin = $this->createAdmin(['role' => 'super-admin']);
        $admin = factory(Admin::class)->create();
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $this->assertCount(0, $admin->roles);

        $response = $this->actingAs($super_admin, 'admin-api')
            ->json('POST', "/api/admin/{$admin->id}/role", [
                'role_id' => $manager_role->id,
            ]);

        $response->assertStatus(200);
        $this->assertCount(1, $admin->fresh()->roles);
        $this->assertAdminHasRole('manager', $admin);
    }

    /** @test */
    public function admin_with_assign_role_permission_can_assign_different_role_to_other_admin()
    {
        $admin = $this->createAdmin([
            'role' => 'manager',
            'permission' => 'assign-role',
        ]);
        $another_admin = factory(Admin::class)->create();
        $executive_role = factory(Role::class)->create(['name' => 'executive']);
        $this->assertCount(0, $another_admin->roles);

        $response = $this->actingAs($admin, 'admin-api')
            ->json('POST', "/api/admin/{$another_admin->id}/role", [
                'role_id' => $executive_role->id,
            ]);

        $response->assertStatus(200);
        $this->assertCount(1, $another_admin->fresh()->roles);
        $this->assertAdminHasRole('executive', $another_admin);
    }

    /** @test */
    public function admin_without_assign_role_permission_cannot_assign_role_to_other_admin()
    {
        $this->withExceptionHandling();
        $admin = factory(Admin::class)->create();
        $another_admin = factory(Admin::class)->create();
        $executive_role = factory(Role::class)->create(['name' => 'executive']);
        $this->assertCount(0, $another_admin->roles);

        $response = $this->actingAs($admin, 'admin-api')
            ->json('POST', "/api/admin/{$another_admin->id}/role", [
                'role_id' => $executive_role->id,
            ]);

        $response->assertStatus(403);
        $this->assertCount(0, $another_admin->fresh()->roles);
        $this->assertAdminDoesNotHaveRole('executive', $another_admin);
    }
}
