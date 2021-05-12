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
        return Inertia::render('Backend/RoleList', [
            'data' => Role::all()->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')),
                    'created_at' => date('d-m-Y h:i:s', strtotime($role->created_at)),
                ];
            }),
        ]);  
        /*$data = Role::all();
        return Inertia::render('Backend/RoleList', ['data' => $data]);*/
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::create(['guard_name' => 'web', 'name' => $request->input('name')]);
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
        $role->syncPermissions($permission);
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

    public function checkRole()
    {
        $user = \Auth::user();

        if($user === null){ return false; }

        $isAdmin = $user->hasAnyRole([
            "Super_administrator",
            "Administrator",
        ]);

        return $isAdmin;
    }

}
