<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $data = Role::all();
        return Inertia::render('Backend/RoleList', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->givePermissionTo($request->input('permission'));
        return redirect()->route('role.list');
    }
}
