<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // i am the teacher and i can do this
    // refactor
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $newUser = new User();

        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->isAdmin = false;

        $newUser->save();

        return response()->json([
            'status' => 1,
            'msg' => 'User created',
            'data' => $newUser
        ], 200);
    }

    // refactor
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

    // refactor
    public function getUsers()
    {
        $users = User::all();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the list of users',
            'data' => $users
        ], 200);
    }

    // refactor
    public function userProfile($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            "status" => 1,
            "msg" => "This is the user profile",
            "data" => $user
        ], 200);
    }

    // refactor
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

    // refactor
    public function delete($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            "status" => 1,
            "msg" => "User successfully deleted"
        ], 200);
    }

    // refactor
    public function update(Request $request, $id)
    {
        /*   $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]); */

        $user = User::findOrFail($id);

        $currentUser = auth()->user();

        if($currentUser->id != $user->id) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
    
            $user->save();

           /*  $currentUser->tokens()->delete(); */

            return response()->json([
                'status' => 1,
                'msg' => 'User updated and you have logged out',
                'data' => $user
            ], 200);
        }

        return response()->json([
            'status' => 0,
            'msg' => 'You cannot update yourself',
        ]);
    }
}
