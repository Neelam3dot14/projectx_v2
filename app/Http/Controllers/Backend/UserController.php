<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        $data = User::all();
        return Inertia::render('Backend/UserList', ['data' => $data]);
    }
    public function create()
    {
        return Inertia::render('Backend/UserCreate');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6|same:password',
            //'role' => 'required',
        ]);
        //$role = $request->get('role');
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        if($user){
            return redirect()->route('user.list')
                ->with('success', 'User Created Successfully.');
        }
        //$user->assignRole($role);
        //$token = $user->createToken('my-app-token')->plainTextToken;
        //return new UserResource($user);
    }
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required'],
        ])->validate();
  
        if ($request->has('id')) {
            User::find($request->input('id'))->update($request->all());
            return redirect()->back()
                    ->with('message', 'Post Updated Successfully.');
        }
    }
    public function destroy(Request $request)
    {
        if ($request->has('id')) {
            User::find($request->input('id'))->delete();
            return redirect()->back();
        }
    }
  
}
