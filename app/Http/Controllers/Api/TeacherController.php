<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class TeacherController extends Controller
{
    // teachers - list function
    public function list()
    {
        $teachers = Teacher::where('superAdmin', '=', 0)->get();

        return response()->json([
            'status' => 1,
            'msg' => 'List of teachers',
            'data' => $teachers
        ], 200);
    }

    // teachers - profile function
    public function profile($id)
    {
        $teacher = Teacher::findOrFail($id);
            
        return response()->json([
            "status" => 1,
            "msg" => "Teacher profile",
            "data" => $teacher
        ], 200);
    }

    // teachers - register / create function
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:teachers',
            'password' => 'required|confirmed'
        ]);

        $newTeacher = new Teacher();

        $newTeacher->name = $request->name;
        $newTeacher->email = $request->email;
        $newTeacher->password = Hash::make($request->password);
        $newTeacher->showPassword = $request->password;

        $newTeacher->save();

        return response()->json([
            'status' => 1,
            'msg' => 'Teacher created',
            'data' =>  $newTeacher
        ], 200);
    }

    // teachers - delete function
    public function delete($id)
    {
        $teacher = Teacher::findOrFail($id);

        $teacher->delete();

        return response()->json([
            "status" => 1,
            "msg" => "Teacher deleted"
        ], 200);
    }

    // teachers - update function
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->password = Hash::make($request->password);
        $teacher->showPassword = $request->password;

        $teacher->update();

        return response()->json([
            'status' => 1,
            'msg' => 'Teacher updated',
            'data' => $teacher
        ], 200);
    }

    // students - list function
    public function listAll() {
        $teachers = Teacher::all();
        
        $students = User::all();

        $allUsers = Arr::collapse([[$teachers], [$students]]);
        
        return response()->json([
            'status' => 1,
            'msg' => 'All users of app',
            'data' => $allUsers
        ]);
    }
}
