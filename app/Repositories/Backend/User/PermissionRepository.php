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
}
