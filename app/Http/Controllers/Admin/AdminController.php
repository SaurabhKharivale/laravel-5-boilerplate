<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::all();

        return response()->json(['admins' => $admins]);
    }

    public function store()
    {
        $this->authorize('create', Admin::class);

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
