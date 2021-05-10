<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\Backend\User\UserResource;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Backend/UserList', [
            'can' => [
                'create_user' => \Auth::user()->can('users.create'),
            ],
            'roles' => Role::pluck('name'),
            'users' => User::all()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles()->pluck('name')->implode(','),
                    'created_at' => date('d-m-Y h:i:s', strtotime($user->created_at)),
                    'can' => [
                        'edit_user' => \Auth::user()->can('users.edit', $user),
                    ]
                ];
            }),
        ]);
        /*$users = User::all();
        $data = UserResource::collection($users);
        return Inertia::render('Backend/UserList', ['data' => $data ]);*/
    }
    public function create()
    {
        $roles = Role::pluck('name');
        return Inertia::render('Backend/UserCreate', ['roles' => $roles ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
            'roles' => 'required',
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole($data['roles']);
        
        return redirect()->route('backend.user.list')
                ->with('message', 'User Created Successfully.');
    }

    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required'],
            'roles' => ['required'],
        ])->validate();
  
        if ($request->has('id')) {
            $user = User::find($request->input('id'));
            $user->update($request->all());
            $user->syncRoles($request->get('roles'));
            return redirect()->back()
                    ->with('message', 'User Updated Successfully.');
        }
    }
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            $user = User::find($request->input('id'));
            $user->roles()->detach();
            $user->delete();
            return redirect()->back()
                ->with('message', 'User Deleted Successfully.');
        }
    }
  
}
