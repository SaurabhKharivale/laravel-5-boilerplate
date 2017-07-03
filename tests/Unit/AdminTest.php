<?php

namespace Tests\Unit;

use App\Role;
use App\Admin;
use App\Permission;
use Tests\TestCase;
use Tests\Support\Helpers\AdminHelpers;

class AdminTest extends TestCase
{
    use AdminHelpers;

    /** @test */
    public function password_and_remember_token_fields_are_not_exposed_when_casting_model_to_array_or_json()
    {
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);

        $array_output = $admin->toArray();

        $this->assertFalse(array_key_exists('password', $array_output));
        $this->assertFalse(array_key_exists('remember_token', $array_output));

        $json_output = json_decode($admin->toJson());

        $this->assertFalse(array_key_exists('password', $json_output));
        $this->assertFalse(array_key_exists('remember_token', $json_output));
    }

    /** @test */
    public function can_check_if_admin_has_certain_permission()
    {
        $admin = factory(Admin::class)->create(['email' => 'admin@example.com']);
        $this->assertFalse($admin->fresh()->hasPermissionTo('view-revenue'));

        $role = $this->createRoleWithPermission('manager', 'view-revenue');
        $admin->assignRole($role);

        $this->assertTrue($admin->fresh()->hasPermissionTo('view-revenue'));
    }

    /** @test */
    public function can_check_if_admin_has_a_specific_role()
    {
        $manager = factory(Role::class)->create(['name' => 'manager']);
        $admin = factory(Admin::class)->create(['email' => 'jane@example.com']);
        $admin->assignRole($manager);

        $this->assertTrue($admin->hasRole('manager'), 'Role not found using string.');
        $this->assertTrue($admin->hasRole($manager), 'Role not found using model instance.');
    }

    /** @test */
    public function it_returns_a_boolean_if_admin_has_any_of_the_given_role_related_to_a_permission()
    {
        $permission = factory(Permission::class)->create(['name' => 'view-revenue']);
        $owner = factory(Role::class)->create(['name' => 'owner']);
        $manager = factory(Role::class)->create(['name' => 'manager']);
        $seller = factory(Role::class)->create(['name' => 'seller']);
        $admin = factory(Admin::class)->create(['email' => 'jane@example.com']);
        $owner->grantPermission($permission);
        $manager->grantPermission($permission);
        $admin->assignRole($owner);
        $admin->assignRole($manager);

        $this->assertTrue($admin->hasRole($permission->roles));
    }

    /** @test */
    public function can_check_if_admin_is_super_admin()
    {
        $admin_one = $this->createSuperAdmin('admin@example.com');
        $admin_two = factory(Admin::class)->create();

        $this->assertTrue($admin_one->isSuperAdmin());
        $this->assertFalse($admin_two->isSuperAdmin());
    }
}
