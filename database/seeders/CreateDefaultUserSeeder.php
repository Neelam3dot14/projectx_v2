<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Team;
use App\Models\Role;

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
        DB::table('teams')->truncate();

        $user1 = tap(User::create([
        	    'name' => 'Super Admin',
        	    'email' => 'superadmin@admin.com',
        	    'password' => bcrypt('superadmin123')
            ]), function (User $user1) {
                $this->createTeam($user1);
            });

        $user2 = tap(User::create([
        	    'name' => 'Admin',
        	    'email' => 'admin@admin.com',
        	    'password' => bcrypt('admin123')
            ]), function (User $user2) {
                $this->createTeam($user2);
            });

        $user3 = tap(User::create([
        	    'name' => 'Executive',
        	    'email' => 'executive@admin.com',
        	    'password' => bcrypt('executive123')
            ]), function (User $user3) {
                $this->createTeam($user3);
            });

        $user4 = tap(User::create([
        	    'name' => 'Member',
        	    'email' => 'member@admin.com',
        	    'password' => bcrypt('member123')
            ]), function (User $user4) {
                $this->createTeam($user4);
            });

        $user5 = tap(User::create([
                'name' => 'Test',
                'email' => 'test@test.com',
                'password' => bcrypt('secret')
            ]), function (User $user5) {
                $this->createTeam($user5);
            });

        $user6 = tap(User::create([
                'name' => 'Test 2',
                'email' => 'test2@test.com',
                'password' => bcrypt('secret')
            ]), function (User $user6) {
                $this->createTeam($user6);
            });

        $user7 = tap(User::create([
                'name' => 'Test 3',
                'email' => 'test3@test.com',
                'password' => bcrypt('secret')
            ]), function (User $user7) {
                $this->createTeam($user7);
            });

        $user8 = tap(User::create([
                'name' => 'Test 4',
                'email' => 'test4@test.com',
                'password' => bcrypt('secret')
            ]), function (User $user8) {
                $this->createTeam($user8);
            });

        User::find(1)->assignRoles(config('access.users.super_admin_role'));
        //assigning multiple roles to user i.e. Admin, Executive, Member
        User::find(2)->assignRoles(config('access.users.admin_role'), config('access.users.executive_role'), config('access.users.member_role'));
        User::find(3)->assignRoles(config('access.users.executive_role'), config('access.users.member_role'));
        User::find(4)->assignRoles(config('access.users.member_role'));
        User::find(5)->assignRoles(config('access.users.member_role'));
        User::find(6)->assignRoles(config('access.users.member_role'));
        User::find(7)->assignRoles(config('access.users.member_role'));
        User::find(8)->assignRoles(config('access.users.member_role'));

        Schema::enableForeignKeyConstraints();
    }

    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
