<?php

namespace Tests\Feature\Backend\Permissions;

use App\Admin;
use App\Role;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;
use Tests\Support\Assertions\AdminAssertions;

class ViewAdminDetailsTest extends TestCase
{
    use AdminHelpers, AdminAssertions;

    /** @test */
    public function super_admin_can_get_detials_of_other_admins()
    {
        $super_admin = $this->createSuperAdmin('super@admin.com');
        factory(Admin::class)->create(['email' => 'first@admin.com']);
        factory(Admin::class)->create(['email' => 'second@admin.com']);
        factory(Admin::class)->create(['email' => 'third@admin.com']);

        $response = $this->actingAs($super_admin, 'admin-api')->json('GET', '/api/admin');

        $response->assertStatus(200);
        $response->assertJson([
            'admins' => [
                ['email' => 'super@admin.com'],
                ['email' => 'first@admin.com'],
                ['email' => 'second@admin.com'],
                ['email' => 'third@admin.com'],
            ]
        ]);
    }

    /** @test */
    public function admin_with_view_permission_can_get_details_of_other_admins()
    {
        $role = $this->createRoleWithPermission('manager', 'view-admin-details');
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        $admin->assignRole($role);
        factory(Admin::class)->create(['email' => 'first@admin.com']);
        factory(Admin::class)->create(['email' => 'second@admin.com']);
        factory(Admin::class)->create(['email' => 'third@admin.com']);

        $response = $this->actingAs($admin, 'admin-api')->json('GET', '/api/admin');

        $response->assertStatus(200);
        $response->assertJson([
            'admins' => [
                ['email' => 'admin@example.com'],
                ['email' => 'first@admin.com'],
                ['email' => 'second@admin.com'],
                ['email' => 'third@admin.com'],
            ]
        ]);
    }

    /** @test */
    public function admin_without_view_permission_cannot_get_details_of_other_admins()
    {
        $this->withExceptionHandling();
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        factory(Admin::class)->create(['email' => 'first@admin.com']);
        factory(Admin::class)->create(['email' => 'second@admin.com']);
        factory(Admin::class)->create(['email' => 'third@admin.com']);

        $response = $this->actingAs($admin, 'admin-api')->json('GET', '/api/admin');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_roles_and_their_associated_permissions_are_contained_within_admin_details()
    {
        $super_admin = $this->createSuperAdmin('super@admin.com');
        $admin_one = factory(Admin::class)->create(['email' => 'first@admin.com']);
        $manager_role = $this->createRoleWithPermissions('manager', ['view-revenue', 'assign-role']);
        $admin_one->assignRole($manager_role);

        $response = $this->actingAs($super_admin, 'admin-api')->json('GET', '/api/admin');

        $response->assertJson([
            'admins' => [
                [
                    'email' => 'super@admin.com',
                    'roles' => [
                        ['name' => 'super-admin'],
                    ]
                ],
                [
                    'email' => 'first@admin.com',
                    'roles' => [
                        [
                            'name' => 'manager',
                            'permissions' => [
                                ['name' => 'view-revenue'],
                                ['name' => 'assign-role'],
                            ]
                        ]
                    ]
                ],
            ]
        ]);
    }
}
