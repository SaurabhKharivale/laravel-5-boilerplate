<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function removeRole(Admin $admin)
    {
        $this->authorize('remove', Admin::class);

        try{
            $role = Role::findOrFail(request('role_id'));
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Unable to process your request.'
            ], 404);
        }

        $admin->roles()->detach($role);

        return response()->json([
            'message' => 'Admin role removed.'
        ], 200);
    }
}
