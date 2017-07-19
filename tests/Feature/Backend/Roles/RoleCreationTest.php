<?php

namespace Tests\Feature\Backend\Roles;

use App\Role;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;

class RoleCreationTest extends TestCase
{
    use AdminHelpers;

    /** @test */
    public function admin_with_create_permission_can_create_new_roles()
    {
        $admin = $this->createAdmin([
            'role' => 'owner',
            'permission' => 'create-role',
        ]);
        $this->assertEquals(1, Role::count());

        $response = $this->actingAs($admin, 'admin-api')->json('POST', '/api/role', [
            'label' => 'Manager',
            'name' => 'manager',
            'description' => 'A role for managers',
        ]);

        $this->assertEquals(2, Role::count());
        $response->assertStatus(201);
        $role = Role::latest('id')->first();
        $this->assertEquals('Manager', $role->label);
        $this->assertEquals('manager', $role->name);
        $this->assertEquals('A role for managers', $role->description);
        $response->assertJson([
            'message' => 'Role created.',
            'type' => 'success',
        ]);
    }

    /** @test */
    public function super_admin_can_create_new_roles()
    {
        $super_admin = $this->createAdmin(['role' => 'super-admin']);
        $this->assertEquals(1, Role::count());

        $response = $this->actingAs($super_admin, 'admin-api')->json('POST', '/api/role', [
            'label' => 'Manager',
            'name' => 'manager',
            'description' => 'A role for managers',
        ]);

        $this->assertEquals(2, Role::count());
        $response->assertStatus(201);
        $role = Role::latest('id')->first();
        $this->assertEquals('Manager', $role->label);
        $this->assertEquals('manager', $role->name);
        $this->assertEquals('A role for managers', $role->description);
        $response->assertJson([
            'message' => 'Role created.',
            'type' => 'success',
        ]);
    }

    /** @test */
    public function admin_without_create_permission_cannot_create_new_roles()
    {
        $this->withExceptionHandling();
        $admin = $this->createAdmin();
        $this->assertEquals(0, Role::count());

        $response = $this->actingAs($admin, 'admin-api')->json('POST', '/api/role', [
            'label' => 'Manager',
            'name' => 'manager',
            'description' => 'A role for managers',
        ]);

        $response->assertStatus(403);
        $this->assertEquals(0, Role::count());
    }

    /** @test */
    public function guest_user_cannot_create_new_roles()
    {
        $this->withExceptionHandling();
        $this->assertEquals(0, Role::count());

        $response = $this->json('POST', '/api/role', [
            'label' => 'Manager',
            'name' => 'manager',
            'description' => 'A role for managers',
        ]);

        $response->assertStatus(401);
        $this->assertEquals(0, Role::count());
    }

    /** @test */
    public function role_name_is_required()
    {
        $this->withExceptionHandling();
        $admin = $this->createAdmin(['role' => 'super-admin']);
        $this->assertEquals(1, Role::count());

        $response = $this->actingAs($admin, 'admin-api')->json('POST', '/api/role', $this->validParams([
            'name' => '',
        ]));

        $this->assertEquals(1, Role::count());
        $response->assertStatus(422);
        $response->assertSeeText('name field is required');
    }

    /** @test */
    public function role_name_must_be_at_least_3()
    {
        $this->withExceptionHandling();
        $admin = $this->createAdmin(['role' => 'super-admin']);
        $this->assertEquals(1, Role::count());

        $response = $this->actingAs($admin, 'admin-api')->json('POST', '/api/role', $this->validParams([
            'name' => 'ab',
        ]));

        $this->assertEquals(1, Role::count());
        $response->assertStatus(422);
        $response->assertSeeText('name must be at least 3 characters');
    }

    /** @test */
    public function role_label_is_required()
    {
        $this->withExceptionHandling();
        $admin = $this->createAdmin(['role' => 'super-admin']);
        $this->assertEquals(1, Role::count());

        $response = $this->actingAs($admin, 'admin-api')->json('POST', '/api/role', $this->validParams([
            'label' => '',
        ]));

        $this->assertEquals(1, Role::count());
        $response->assertStatus(422);
        $response->assertSeeText('label field is required');
    }

    /** @test */
    public function role_label_must_be_at_least_3()
    {
        $this->withExceptionHandling();
        $admin = $this->createAdmin(['role' => 'super-admin']);
        $this->assertEquals(1, Role::count());

        $response = $this->actingAs($admin, 'admin-api')->json('POST', '/api/role', $this->validParams([
            'label' => 'ab',
        ]));

        $this->assertEquals(1, Role::count());
        $response->assertStatus(422);
        $response->assertSeeText('label must be at least 3 characters');
    }

    public function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'manager',
            'label' => 'Manager',
            'description' => 'A role for manager.',
        ], $overrides);
    }
}
