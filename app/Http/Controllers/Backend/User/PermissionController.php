<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\Backend\User\PermissionRepository;
use App\Http\Resources\Backend\User\PermissionResource;
use Inertia\Inertia;

class PermissionController extends Controller
{
    public $permissionRepo;
    public function index() {
        $this->permissionRepo = new PermissionRepository();
        $permission = $this->permissionRepo->getAllPermissions();
        return Inertia::render('Backend/PermissionList', ['permission' => $permission]);
    }

    public function store(Request $request) 
    {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
        $name = $request['name'];
        $permission = Permission::create(['guard_name' => 'web', 'name' => $name ]);
        return redirect()->back()
                ->with('message', 'Permission Created Successfully.');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
        $permission = Permission::findOrFail($id);
        $input = $request->all();
        $permission->fill($input)->save();
        return new PermissionResource($permission);
    }

    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            Permission::find($request->input('id'))->delete();
            return redirect()->back()
                ->with('message', 'Permission Deleted Successfully.');
        }
    }

    public function getAllPermissionByName()
    {
        $result = Permission::select('name')->get();
        $permission = [];
        foreach($result as $data){
            array_push($permission, $data['name']);
        }
        return $permission;
        //return $result;
    }
}
