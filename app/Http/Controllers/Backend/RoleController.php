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
}
