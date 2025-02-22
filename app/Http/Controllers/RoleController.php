<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Barryvdh\Debugbar\Facades\Debugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {

        $roles = Role::all();

        return view('admin.role.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $role  = Role::create($request->validate([
            'name' => ['required', 'min:3'],
        ]));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.role.edit', compact('role', 'permissions'));
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $role->permissions()->sync($request->permissions);
        return back();
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return to_route('admin.roles.index');
    }
}
