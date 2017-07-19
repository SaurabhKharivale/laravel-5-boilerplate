<?php

namespace App\Http\Controllers\Backend;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json([
            'roles' => Role::all(),
        ]);
    }

    public function store()
    {
        $this->authorize('create', Role::class);

        $this->validate(request(), [
            'name' => 'required|min:3',
            'label' => 'required|min:3',
        ]);

        Role::create([
            'name' => request('name'),
            'label' => request('label'),
            'description' => request('description'),
        ]);

        return response()->json([
            'message' => 'Role created.',
            'type' => 'success',
        ], 201);
    }
}
