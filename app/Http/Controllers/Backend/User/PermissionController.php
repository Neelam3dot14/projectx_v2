<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Repositories\Backend\User\PermissionRepository;
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
        $permission = Permission::create(['name' => $name ]);
        return redirect()->route('backend.permission.list')
                ->with('message', 'Permission Created Successfully.');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);
  
        if ($request->has('id')) {
            Permission::find($request->input('id'))->update($request->all());
            return redirect()->back()
                    ->with('message', 'Permission Updated Successfully.');
        }
    }

    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            Permission::find($request->input('id'))->delete();
            return redirect()->back();
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
