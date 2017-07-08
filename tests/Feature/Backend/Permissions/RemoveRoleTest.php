<?php

namespace Tests\Feature\Backend\Permissions;

use App\Role;
use App\Admin;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;
use Tests\Support\Assertions\AdminAssertions;

class RemoveRoleTest extends TestCase
{
    use AdminHelpers, AdminAssertions;

    /** @test */
    public function super_admin_can_remove_admin_role()
    {
        $super_admin = $this->createAdmin(['role' => 'super-admin']);
        $admin = factory(Admin::class)->create();
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $admin->assignRole($manager_role);
        $this->assertAdminHasRole('manager', $admin);

        $response = $this->actingAs($super_admin, 'admin-api')
            ->json('DELETE', "/api/admin/{$admin->id}/role", [
                'role_id' => $manager_role->id,
            ]);

        $response->assertStatus(200);
        $this->assertCount(0, $admin->fresh()->roles);
        $this->assertAdminDoesNotHaveRole('manager', $admin);
    }

    /** @test */
    public function admin_with_remove_permission_can_remove_admin_role()
    {
        $admin = $this->createAdmin(['role' => 'owner', 'permission' => 'remove-role']);
        $another_admin = factory(Admin::class)->create();
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $another_admin->assignRole($manager_role);
        $this->assertCount(1, $another_admin->fresh()->roles);
        $this->assertAdminHasRole('manager', $another_admin);

        $response = $this->actingAs($admin, 'admin-api')
            ->json('DELETE', "/api/admin/{$another_admin->id}/role", [
                'role_id' => $manager_role->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Admin role removed.']);
        $this->assertCount(0, $another_admin->fresh()->roles);
        $this->assertAdminDoesNotHaveRole('manager', $another_admin);
    }

    /** @test */
    public function role_should_not_be_removed_if_request_does_not_contain_role_id()
    {
        $this->withExceptionHandling();
        $admin = $this->createAdmin(['role' => 'owner', 'permission' => 'remove-role']);
        $another_admin = factory(Admin::class)->create();
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $another_admin->assignRole($manager_role);
        $this->assertCount(1, $another_admin->fresh()->roles);
        $this->assertAdminHasRole('manager', $another_admin);

        $response = $this->actingAs($admin, 'admin-api')
            ->json('DELETE', "/api/admin/{$another_admin->id}/role", [
                'role_id' => null,
            ]);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Unable to process your request.']);
        $this->assertCount(1, $another_admin->fresh()->roles);
        $this->assertAdminHasRole('manager', $another_admin);
    }

    /** @test */
    public function admin_without_remove_permission_cannot_remove_admin_role()
    {
        $this->withExceptionHandling();
        $admin = factory(Admin::class)->create();
        $another_admin = factory(Admin::class)->create();
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $another_admin->assignRole($manager_role);
        $this->assertAdminHasRole('manager', $another_admin);

        $response = $this->actingAs($admin, 'admin-api')
            ->json('DELETE', "/api/admin/{$another_admin->id}/role", [
                'role_id' => $manager_role->id,
            ]);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'This action is unauthorized.']);
        $this->assertCount(1, $another_admin->fresh()->roles);
        $this->assertAdminHasRole('manager', $another_admin);
    }
}
