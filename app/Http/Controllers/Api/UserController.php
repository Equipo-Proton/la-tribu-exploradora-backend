<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    // create user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            'status' => 1,
            'msg' => 'User created',
            'data' => $user
        ], 200);
    }

    // generate token-login
    public function login(Request $request)
    {
        // validation of form login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // find user with the same email
        $user = User::where('email', '=', $request->email)->first();

        // check if form password is equal to user password on database
        if(isset($user->id)) {
            if(Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 1,
                    'msg' => 'You are logged in',
                    'user' => $user,
                    'access_token' => $token
                ], 200);
            }

            return response()->json([
                'status' => 0,
                'msg' => 'Incorrect password'
            ], 404);
        }

        return response()->json([
            'status' => 0,
            'msg' => 'User no registered'
        ], 404);
    }

    public function getUsers()
    {
    }

    public function userProfile()
    {
        
        $user = auth()->user();

        if($user){
            return response()->json([
                "status" => 1,
                "msg" => "This is the user profile",
                "data" => $user 
            ],200);  
        }
        
        return response()->json([
            "status" => 0,
            "msg" => "There are no users authenticated"  
        ]);   
    }

    public function logout()
    {
        $user = auth()->user();
        auth()->user()->tokens()->delete();

        return response()->json([
            "status" => 1,
            "msg" => "You are logged out",
            "data" => $user
        ], 200);
    }

    public function delete() {

    }

    public function update() {

    }
}
