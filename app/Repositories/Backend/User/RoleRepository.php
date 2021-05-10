<?php


namespace App\Repositories\Backend\User;

use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function getAllRoles($paginate = 10)
    {
        $result = Role::all(); //paginate($paginate);
        return $result;
    }    
}
