<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //use HasPermissionsTrait; 
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function permissions() {

        return $this->belongsToMany(Permission::class,'role_has_permissions');
    }

    protected function getAllPermissions(array $permissions) 
    {
        return Permission::whereIn('name', $permissions)->get();
    }

    public function givePermissionsTo(array $permissions) 
    {
        $permissions = $this->getAllPermissions($permissions);
        if($permissions === null) {
          return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }
    
    public function refreshPermissions( array $permissions ) 
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }
}
