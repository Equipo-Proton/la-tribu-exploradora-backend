<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = new User();
        $user = auth()->user();

        if($user) {
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
       
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', '=', $request->email)->first();

        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
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
        $users = User::all();
        $user = auth()->user();

        if($user) {
            return response()->json([
                'status' => 1,
                'msg' => 'This is the list of users',
                'data' => $users
            ], 200);
        }

        return response()->json([
            'status' => 0,
            'msg' => 'You are not logged in',
        ], 401);
    }

    public function userProfile()
    {
        $user = auth()->user();

        if ($user) {
            return response()->json([
                "status" => 1,
                "msg" => "This is the user profile",
                "data" => $user
            ], 200);
        }

        return response()->json([
            "status" => 0,
            "msg" => "There are no users authenticated"
        ], 401);
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

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if(auth()->user()){
            $user->delete();

            return response()->json([
                "status" => 1,
                "msg" => "User successfully deleted"
            ],200);
        }
        
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        $user = User::findOrFail($id);

        if(auth()->user()) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
    
            $user->update();
    
            return response()->json([
                'status' => 1,
                'msg' => 'User updated',
                'data' => $user
            ], 200);
        }
    }
}
