<?php

namespace Tests\Feature\Backend\Permissions;

use App\Role;
use App\Admin;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;

class RemoveRoleTest extends TestCase
{
    use AdminHelpers;

    /** @test */
    public function super_admin_can_remove_admin_role()
    {
        $super_admin = $this->createSuperAdmin('admin@example.com');
        $manager = factory(Admin::class)->create(['email' => 'manager@example.com']);
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $manager->assignRole($manager_role);
        $this->assertTrue($manager->fresh()->roles->contains('name', 'manager'));

        $response = $this->actingAs($super_admin, 'admin-api')
            ->json('DELETE', "/api/admin/{$manager->id}/role", [
                'role_id' => $manager_role->id,
            ]);

        $response->assertStatus(200);
        $this->assertCount(0, $manager->fresh()->roles);
    }

    /** @test */
    public function admin_with_remove_permission_can_remove_admin_role()
    {
        $role = $this->createRoleWithPermission('owner', 'remove-role');
        $admin = factory(Admin::class)->create();
        $admin->assignRole($role);
        $manager = factory(Admin::class)->create();
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $manager->assignRole($manager_role);
        $this->assertCount(1, $manager->fresh()->roles);
        $this->assertTrue($manager->fresh()->roles->contains('name', 'manager'));

        $response = $this->actingAs($admin, 'admin-api')
            ->json('DELETE', "/api/admin/{$manager->id}/role", [
                'role_id' => $manager_role->id,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Admin role removed.']);
        $this->assertCount(0, $manager->fresh()->roles);
        $this->assertFalse($manager->fresh()->roles->contains('name', 'manager'));
    }

    /** @test */
    public function role_should_not_be_removed_if_request_does_not_contain_role_id()
    {
        $this->withExceptionHandling();
        $role = $this->createRoleWithPermission('owner', 'remove-role');
        $admin = factory(Admin::class)->create();
        $admin->assignRole($role);
        $manager = factory(Admin::class)->create();
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $manager->assignRole($manager_role);
        $this->assertCount(1, $manager->fresh()->roles);
        $this->assertTrue($manager->fresh()->roles->contains('name', 'manager'));

        $response = $this->actingAs($admin, 'admin-api')
            ->json('DELETE', "/api/admin/{$manager->id}/role", [
                'role_id' => null,
            ]);

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Unable to process your request.']);
        $this->assertCount(1, $manager->fresh()->roles);
        $this->assertTrue($manager->fresh()->roles->contains('name', 'manager'));
    }

    /** @test */
    public function admin_without_remove_permission_cannot_remove_admin_role()
    {
        $this->withExceptionHandling();
        $admin = factory(Admin::class)->create();
        $manager = factory(Admin::class)->create();
        $manager_role = factory(Role::class)->create(['name' => 'manager']);
        $manager->assignRole($manager_role);
        $this->assertTrue($manager->fresh()->roles->contains('name', 'manager'));

        $response = $this->actingAs($admin, 'admin-api')
            ->json('DELETE', "/api/admin/{$manager->id}/role", [
                'role_id' => $manager_role->id,
            ]);

        $response->assertStatus(403);
        $response->assertJson(['message' => 'This action is unauthorized.']);
        $this->assertCount(1, $manager->fresh()->roles);
        $this->assertTrue($manager->fresh()->roles->contains('name', 'manager'));
    }
}
