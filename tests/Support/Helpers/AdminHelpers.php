<?php

namespace Tests\Support\Helpers;

use App\Role;
use App\Admin;
use App\Permission;

trait AdminHelpers
{
    public function createSuperAdmin($email)
    {
        $super_admin = factory(Role::class)->create(['name' => 'super-admin']);
        $admin = factory(Admin::class)->create(['email' => $email]);
        $admin->assignRole($super_admin);

        return $admin;
    }

    public function createRoleWithPermissions($role, $permissions = [])
    {
        $role = factory(Role::class)->create(['name' => $role]);

        if($permissions) {
            foreach ($permissions as $permission) {
                $role->grantPermission(
                    factory(Permission::class)->create(['name' => $permission])
                );
            }
        }

        return $role;
    }

    public function createRoleWithPermission($role, $permission = null)
    {
        $permissions = $permission ? [$permission] : [];

        return $this->createRoleWithPermissions($role, $permissions);
    }
}
