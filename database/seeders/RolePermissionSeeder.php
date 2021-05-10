<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $toTruncate = ['roles', 'user_has_roles', 'permissions', 'role_has_permissions'];

    public function run()
    {
        Schema::disableForeignKeyConstraints();

        //Truncating Role, permission related table
        foreach($this->toTruncate as $table) {
            DB::table($table)->truncate();
        }

        // Create Roles
        $superAdmin = Role::create(['name' => config('access.users.super_admin_role')]);
        $admin = Role::create(['name' => config('access.users.admin_role')]);
        $executive = Role::create(['name' => config('access.users.executive_role')]);
        $member = Role::create(['name' => config('access.users.member_role')]);

        $member_permissions = config('access.member_permissions');
        $executive_permissions = config('access.executive_permissions');
        $admin_permissions = config('access.admin_permissions');

        // Create Permissions
        $permissions = array_merge($member_permissions, $executive_permissions, $admin_permissions);
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        //Assign Permissions to Member
        $member->givePermissionsTo($member_permissions);

        //Assign Permissions to Executive
        $executive->givePermissionsTo($executive_permissions);

        // Assign Permissions to Admin
        $admin->givePermissionsTo($admin_permissions);

        // ALWAYS GIVE SUPER ADMIN ROLE ALL PERMISSIONS
        $superAdmin->givePermissionsTo(Permission::all());

        Schema::enableForeignKeyConstraints();
    }
}
