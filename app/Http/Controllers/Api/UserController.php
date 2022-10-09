<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // students - login function
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $student = User::where('email', '=', $request->email)->first();

        $teacher = Teacher::where('email', '=', $request->email)->first();

        if (isset($student->id)) {
            if (Hash::check($request->password, $student->password)) {
                $token = $student->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 1,
                    'msg' => 'Student is login',
                    'data' => $student,
                    'access_token' => $token
                ], 200);
            }

            return response()->json([
                'status' => 0,
                'msg' => 'Incorrect password'
            ], 404);
        }

        if (isset($teacher->id)) {
            if (Hash::check($request->password, $teacher->password)) {
                $token = $teacher->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'status' => 1,
                    'msg' => 'Teacher is login',
                    'data' => $teacher,
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

    // teachers - logout function
    public function logout()
    {
        $teacher = auth()->user();

        $students = $teacher->users;

        auth()->user()->tokens()->delete();

        foreach($students as $student) {
            $student->tokens()->delete();
        }
           
        return response()->json([
            'status' => 1,
            'msg' => "User logout",
            'data' => $teacher,
        ], 200);
    }

    // students - list function
    public function list()
    {
        $teacher = auth()->user();

        $students = $teacher->users;

        return response()->json([
            'status' => 1,
            'msg' => 'List of students',
            'data' => $students
        ], 200);
    }

    // students - profile function
    public function profile($id)
    {
        $teacher = auth()->user();

        $student = $teacher->users
            ->find($id);

        return response()->json([
            "status" => 1,
            "msg" => "Student profile",
            "data" => $student
        ], 200);
    }

    // students - register / create function
    public function register(Request $request)
    {
        $teacher = auth()->user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $newStudent = new User();

        $newStudent->name = $request->name;
        $newStudent->email = $request->email;
        $newStudent->password = Hash::make($request->password);
        $newStudent->showPassword = $request->password;
        $newStudent->teacher_id = $teacher->id;

        $newStudent->save();

        return response()->json([
            'status' => 1,
            'msg' => 'Student created',
            'data' =>  $newStudent
        ], 200);
    }

    // students - delete function
    public function delete($id)
    {
        $teacher = auth()->user();

        $student = $teacher->users
            ->find($id);

        $student->delete();

        return response()->json([
            "status" => 1,
            "msg" => "Student deleted"
        ], 200);
    }

     // students - update function
    public function update(Request $request, $id)
    {
        $teacher = auth()->user();

        $student = $teacher->users
            ->find($id);

        $student ->name = $request->name;
        $student ->email = $request->email;
        $student ->password = Hash::make($request->password);
        $student ->showPassword = $request->password;

        $student ->update();


        return response()->json([
            'status' => 1,
            'msg' => 'Student updated',
            'data' => $student 
        ], 200);
    }
}
