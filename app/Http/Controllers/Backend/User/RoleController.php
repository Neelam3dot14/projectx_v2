<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
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
        return redirect()->route('backend.role.list')
                ->with('message', 'Role Created Successfully.');
    }

    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $permission = $request->input('permission');
        $role->givePermissionTo($permission);
        return redirect()->back()
                ->with('message', 'Role Updated Successfully.');
    }

    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            $role = Role::find($request->input('id'));
            $role->permissions()->detach();
            $role->delete();
            return redirect()->back()
                ->with('message', 'Role Deleted Successfully.');
        }
    }

    public function getAllRoleNames()
    {
        $result = Role::pluck('name');
        return $result;
    }

    

}
