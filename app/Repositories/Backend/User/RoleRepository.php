<?php


namespace App\Repositories\Backend\User;

use App\Models\Role;

class RoleRepository
{
    public function getAllRoles($paginate = 10)
    {
        $result = Role::all(); //paginate($paginate);
        return $result;
    }    
}
