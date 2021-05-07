<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class CreateDefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        //trucating user table before proceeding
        DB::table('users')->truncate();

        $user1 = User::create([
        	'name' => 'Super Admin',
        	'email' => 'superadmin@admin.com',
        	'password' => bcrypt('superadmin123')
        ]);

        $user2 = User::create([
        	'name' => 'Admin',
        	'email' => 'admin@admin.com',
        	'password' => bcrypt('admin123')
        ]);

        $user3 = User::create([
        	'name' => 'Executive',
        	'email' => 'executive@admin.com',
        	'password' => bcrypt('executive123')
        ]);

        $user4 = User::create([
        	'name' => 'Member',
        	'email' => 'member@admin.com',
        	'password' => bcrypt('member123')
        ]);

        $user5 = User::create([
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => bcrypt('secret')
        ]);

        $user6 = User::create([
            'name' => 'Test 2',
            'email' => 'test2@test.com',
            'password' => bcrypt('secret')
        ]);

        $user7 = User::create([
            'name' => 'Test 3',
            'email' => 'test3@test.com',
            'password' => bcrypt('secret')
        ]);

        $user8 = User::create([
            'name' => 'Test 4',
            'email' => 'test4@test.com',
            'password' => bcrypt('secret')
        ]);

        User::find(1)->assignRole(config('access.users.super_admin_role'));
        //assigning multiple roles to user i.e. Admin, Executive, Member
        User::find(2)->assignRole(config('access.users.admin_role'), config('access.users.executive_role'), config('access.users.member_role'));
        User::find(3)->assignRole(config('access.users.executive_role'), config('access.users.member_role'));
        User::find(4)->assignRole(config('access.users.member_role'));
        User::find(5)->assignRole(config('access.users.member_role'));
        User::find(6)->assignRole(config('access.users.member_role'));
        User::find(7)->assignRole(config('access.users.member_role'));
        User::find(8)->assignRole(config('access.users.member_role'));

        Schema::enableForeignKeyConstraints();
    }
}
