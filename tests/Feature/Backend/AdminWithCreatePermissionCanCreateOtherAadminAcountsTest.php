<?php

namespace Tests\Feature\Backend;

use App\Admin;
use App\Role;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;
use Tests\Support\Assertions\AdminAssertions;

class AdminWithCreatePermissionCanCreateOtherAdminsTest extends TestCase
{
    use AdminHelpers, AdminAssertions;

    /** @test */
    public function only_admins_with_create_permission_can_create_new_admin_accounts()
    {
        $manager = $this->createRoleWithPermission('manager', 'create-admin');
        $admin = factory(Admin::class)->create();
        $admin->assignRole($manager);
        $this->actingAs($admin, 'admin-api');
        $this->assertCount(1, Admin::all());

        $response = $this->post('/api/admin', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(201);
        $this->assertCount(2, Admin::all());
        $this->assertAdminUserExists('jane@example.com');
        $response->assertJson([
            'message' => 'New admin user created.',
            'type' => 'success',
        ]);
    }

    /** @test */
    public function super_admin_can_create_other_admin_user_even_when_no_permission_is_assigned()
    {
        $super_admin = factory(Admin::class)->create();
        $role = factory(Role::class)->create(['name' => 'super-admin']);
        $super_admin->assignRole($role);
        $this->actingAs($super_admin, 'admin-api');
        $this->assertCount(1, Admin::all());

        $response = $this->post('/api/admin', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(201);
        $this->assertCount(2, Admin::all());
        $this->assertAdminUserExists('jane@example.com');
        $response->assertJson([
            'message' => 'New admin user created.',
            'type' => 'success',
        ]);
    }

    /** @test */
    public function super_admin_can_get_detials_of_other_admins()
    {
        $super_admin = $this->createSuperAdmin('super@admin.com');
        $this->actingAs($super_admin, 'admin-api');
        factory(Admin::class)->create(['email' => 'first@admin.com']);
        factory(Admin::class)->create(['email' => 'second@admin.com']);
        factory(Admin::class)->create(['email' => 'third@admin.com']);

        $response = $this->get('/api/admin');

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
    public function admin_not_having_create_permissions_cannot_create_new_admins()
    {
        $this->withExceptionHandling();
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin-api');
        $this->assertCount(1, Admin::all());

        $response = $this->post('/api/admin', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(403);
        $this->assertCount(1, Admin::all());
        $this->assertAdminUserDoesNotExists('jane@example.com');
    }
}
