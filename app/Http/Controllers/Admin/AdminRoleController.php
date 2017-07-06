<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminRoleController extends Controller
{
    public function assignRole(Admin $admin)
    {
        $this->authorize('assign', Admin::class);

        $role = Role::find(request('role_id'));

        $admin->assignRole($role);

        return response()->json([
        ], 200);
    }
}
