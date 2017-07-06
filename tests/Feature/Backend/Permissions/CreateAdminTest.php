<?php

namespace Tests\Feature\Backend\Permissions;

use App\Admin;
use App\Role;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;
use Tests\Support\Assertions\AdminAssertions;

class CreateAdminTest extends TestCase
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

        $response = $this->json('POST', '/api/admin', [
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
    public function admin_not_having_create_permissions_cannot_create_new_admins()
    {
        $this->withExceptionHandling();
        $admin = factory(Admin::class)->create();
        $this->actingAs($admin, 'admin-api');
        $this->assertCount(1, Admin::all());

        $response = $this->json('POST', '/api/admin', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(403);
        $this->assertCount(1, Admin::all());
        $this->assertAdminUserDoesNotExists('jane@example.com');
    }

    /** @test */
    public function cannot_create_multiple_admin_account_with_same_email_address()
    {
        $this->withExceptionHandling();
        $admin_one = $this->createSuperAdmin('admin@example.com');
        $this->actingAs($admin_one, 'admin-api');
        $admin_two = factory(Admin::class)->create(['email' => 'jane@example.com']);
        $this->assertCount(2, Admin::all());

        $response = $this->json('POST', '/api/admin', [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(422);
        $this->assertCount(2, Admin::all());
        $response->assertJson([
            'email' => ['The email has already been taken.']
        ]);
    }

    /** @test */
    public function it_should_not_pass_validation_for_empty_admin_details()
    {
        $this->withExceptionHandling();
        $admin = $this->createSuperAdmin('admin@example.com');
        $this->actingAs($admin, 'admin-api');
        $this->assertCount(1, Admin::all());

        $response = $this->json('POST', '/api/admin', [
            'first_name' => null,
            'last_name' => null,
            'email' => null,
        ]);

        $response->assertStatus(422);
        $this->assertCount(1, Admin::all());
        $response->assertJson([
            'first_name' => ['The first name field is required.'],
            'last_name' => ['The last name field is required.'],
            'email' => ['The email field is required.'],
        ]);
    }

    /** @test */
    public function first_and_last_name_must_have_atleast_3_characters()
    {
        $this->withExceptionHandling();
        $admin = $this->createSuperAdmin('admin@example.com');
        $this->actingAs($admin, 'admin-api');
        $this->assertCount(1, Admin::all());

        $response = $this->json('POST', '/api/admin', [
            'first_name' => 'ab',
            'last_name' => 'xy',
            'email' => 'jane@example.com',
        ]);

        $response->assertStatus(422);
        $this->assertCount(1, Admin::all());
        $response->assertJson([
            'first_name' => ['The first name must be at least 3 characters.'],
            'last_name' => ['The last name must be at least 3 characters.'],
        ]);
    }
}
