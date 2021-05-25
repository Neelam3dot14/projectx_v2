<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\Backend\User\PermissionRepository;
use Inertia\Inertia;

class RoleController extends Controller
{
    public $permissionRepo;
    public function index()
    {
        return Inertia::render('Backend/Role/List', [
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

    public function create()
    {
        $this->permissionRepo = new PermissionRepository();
        $permissions = $this->permissionRepo->getAllPermissionByName();
        return Inertia::render('Backend/Role/Create', ['permission' => $permissions]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);
        $role = Role::create(['guard_name' => 'web', 'name' => $request->input('name')]);
        $role->givePermissionTo($request->input('permissions'));
        return redirect()->route('backend.role.list')
                ->with('message', 'Role Created Successfully.');
    }

    public function edit($id)
    {
        $this->permissionRepo = new PermissionRepository();
        $permissions = $this->permissionRepo->getAllPermissionByName();
        $role = Role::findOrFail($id);
        $roleResource = [
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions()->pluck('name'),
        ];
        return Inertia::render('Backend/Role/Edit', ['permission' => $permissions, 'role' => $roleResource ]);
    }

    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required',
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $permissions = $request->input('permissions');
        $role->syncPermissions($permissions);
        return redirect()->route('backend.role.list')
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
