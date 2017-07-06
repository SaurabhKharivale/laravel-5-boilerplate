<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $this->authorize('view', Admin::class);

        $admins = Admin::with('roles', 'roles.permissions')->get();

        return response()->json(['admins' => $admins]);
    }

    public function store()
    {
        $this->authorize('create', Admin::class);

        $this->validate(request(), [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|unique:admins',
        ]);

        Admin::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'password' => bcrypt('secret'),
        ]);

        return response()->json([
            'message' => 'New admin user created.',
            'type' => 'success',
        ], 201);
    }
}
