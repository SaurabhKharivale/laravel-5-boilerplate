<?php

namespace Tests\Support\Helpers;

use App\Role;
use App\Admin;
use App\Permission;

trait AdminHelpers
{
    public function createAdmin($data)
    {
        $email = isset($data['email']) ? $data['email'] : null;
        $admin = $this->createAdminAccount($email);

        if(isset($data['role'])) {
            $role = factory(Role::class)->create(['name' => $data['role']]);
            $admin->assignRole($role);

            if(isset($data['permission'])) {
                $this->attachPermissionToRole($role, $data['permission']);
            }
        }

        return $admin;
    }

    private function attachPermissionToRole($role, $permissions) {
        if(is_string($permissions)) {
            $permission = factory(Permission::class)->create(['name' => $permissions]);
            $role->grantPermission($permission);

            return;
        }

        foreach ($permissions as $permission) {
            $role->grantPermission(factory(Permission::class)->create(['name' => $permission]));
        }
    }

    private function createAdminAccount($email)
    {
        if($email) {
            return factory(Admin::class)->create(['email' => $email]);
        }

        return factory(Admin::class)->create();
    }
}
