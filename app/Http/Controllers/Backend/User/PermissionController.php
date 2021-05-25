<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\Backend\User\PermissionRepository;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public $permissionRepo;
    public function index()
    {
        $this->permissionRepo = new PermissionRepository();
        $permissions = $this->permissionRepo->getAllPermissions();
        //return Inertia::render('Backend/PermissionList', ['permission' => $permission]);
        return Inertia::render('Backend/Permission/List', [
            'permission' => $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'created_at' => date('d-m-Y h:i:s', strtotime($permission->created_at)),
                ];
            }),
        ]);
    }

    public function create()
    {
        return Inertia::render('Backend/Permission/Create');
    }

    public function store(Request $request) 
    {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
        $name = $request['name'];
        $permission = Permission::create(['guard_name' => 'web', 'name' => $name ]);
        return redirect()->route('backend.permission.list')
                ->with('message', 'Permission Created Successfully.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return Inertia::render('Backend/Permission/Edit', ['permission' => $permission]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
        $permission = Permission::findOrFail($id);
        $input = $request->all();
        $permission->fill($input)->save();
        return redirect()->route('backend.permission.list')
                ->with('message', 'Permission Updated Successfully.');
    }

    public function destroy($id)
    {
        Permission::find($id)->delete();
        return redirect()->route('backend.permission.list')
                ->with('message', 'Permission Deleted Successfully.');
    }

    public function getAllPermissionByName()
    {
        $result = Permission::select('name')->get();
        $permission = [];
        foreach($result as $data){
            array_push($permission, $data['name']);
        }
        return $permission;
    }
}
