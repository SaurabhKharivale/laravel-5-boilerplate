<?php

namespace App\Policies;

use App\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(Admin $admin)
    {
        if($admin->isSuperAdmin()) {
            return true;
        }
    }

    public function view(Admin $admin)
    {
        return $admin->hasPermissionTo('view-admin-details');
    }

    public function create(Admin $admin)
    {
        return $admin->hasPermissionTo('create-admin');
    }

    public function assign(Admin $admin)
    {
        return $admin->hasPermissionTo('assign-role');
    }

    public function remove(Admin $admin)
    {
        return $admin->hasPermissionTo('remove-role');
    }
}
