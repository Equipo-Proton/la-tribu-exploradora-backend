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

        $newUser = new User();
        $teacher = auth()->user();

        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->isAdmin = false;
        $newUser->teacher = $teacher->id;

        $newUser->save();

        return response()->json([
            'status' => 1,
            'msg' => 'User created',
            'data' => $newUser
        ], 200);
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
        $teacher = auth()->user();

        $users = User::all()
            ->where('isAdmin', '=', 0)
            ->where('superAdmin', '=', 0)
            ->where('teacher', '=', $teacher->id);

        return response()->json([
            'status' => 1,
            'msg' => 'This is the list of users',
            'data' => $users
        ], 200);
    }

    public function userProfile($id)
    {
        $teacher = auth()->user();

        $user = User::where('isAdmin', '=', 0)
            ->where('superAdmin', '=', 0)
            ->where('teacher', '=', $teacher->id)
            ->findOrFail($id);
            
        return response()->json([
            "status" => 1,
            "msg" => "This is the user profile",
            "data" => $user
        ], 200);
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
        $teacher = auth()->user();

        $user = User::where('isAdmin', '=', 0)
            ->where('superAdmin', '=', 0)
            ->where('teacher', '=', $teacher->id)
            ->findOrFail($id);

        $user->delete();

        return response()->json([
            "status" => 1,
            "msg" => "User successfully deleted"
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $teacher = auth()->user();

        $user = User::where('isAdmin', '=', 0)
            ->where('superAdmin', '=', 0)
            ->where('teacher', '=', $teacher->id)
            ->findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
    
        $user->update();

        return response()->json([
            'status' => 1,
            'msg' => 'User updated and you have logged out',
            'data' => $user
        ], 200);
    }
}
