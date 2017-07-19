<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function before(Admin $admin)
    {
        if($admin->isSuperAdmin()) {
            return true;
        }
    }

    public function create(Admin $admin)
    {
        return $admin->hasPermissionTo('create-role');
    }
}
