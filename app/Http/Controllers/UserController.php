<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function index()
{
    $users = User::with('role')->get()->map(function ($user) {
        return [
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'username' => $user->username,
            'phonenumber' => $user->phonenumber,
            'email' => $user->email,
            'account_status' => $user->account_status,
            'role_id' => $user->role_id,
            'role_name' => $user->role->role_name ?? null, 
        ];
    });

    return response()->json($users);
}

    //check email availability
    public function checkemail(Request $request){

        $email = $request->input('email');
        $emailExist = User::where('email',  $email)->exists();

        return response()->json(['exists' => $emailExist]);
    }

    //check username availability
    public function checkusername(Request $request){
        $username = $request->input('username');
        $usernameExist = User::where('username' , $username)->exists();

        return response()->json(['exists' => $usernameExist]);
    }

    //submit user
    public function store(Request $request){
        $validated = $request->validate([
          'firstname'=>'sometimes|string|max:255',
          'lastname' => 'sometimes|string|max:255',
          'username' => 'sometimes|string|max:255',
          'email' => 'sometimes|string|max:255|unique:users,email',
          'role_id' => 'sometimes|integer|exists:roles,id',
          'phonenumber' => ['sometimes', 'regex:/^(?:\+?63|0)9\d{9}$/'],
          'password' => 'sometimes|string|min:8',
          'account_status' => 'sometimes|string|max:255'
        ]);

        $user = User::create([
            'firstname'=>$validated['firstname'],
            'lastname'=>$validated['lastname'],
            'username'=>$validated['username'],
            'email'=>$validated['email'],
            'role_id'=>$validated['role_id'],
            'phonenumber'=>$validated['phonenumber'],
            'password'=>bcrypt($validated['password']),
            'account_status'=>$validated['account_status'],
        ]);

        return response()->json(['message' => 'user has successfully registered','user' => $user],201);
    }


    //update user account
    public function update(Request $request, string $id){
           $user = User::findOrFail($id);

           $validated = $request->validate([
          'firstname'=>'sometimes|string|max:255',
          'lastname' => 'sometimes|string|max:255',
          'username' => ['sometimes|string', Rule::unique('users' , 'username')->ignore($user->id)],
          'email' => ['sometimes|string', Rule::unique('users','email')->ignore($user->id)],
          'role_id' => 'sometimes|integer|exists:roles,id',
          'phonenumber' => ['sometimes', 'regex:/^(?:\+?63|0)9\d{9}$/'],
          'password' => 'sometimes|string|min:8',
          'account_status' => 'sometimes|string|max:255'
        ]);

        if(isset($validated['password'])){
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message'=> 'user successfully updated', 'user' => $user], 200);
    }
}
