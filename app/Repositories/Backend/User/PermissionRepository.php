<?php


namespace App\Repositories\Backend\User;

use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public function getAllPermissions($paginate = 10)
    {
        $result = Permission::all(); 
        return $result;
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
