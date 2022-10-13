<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Throwable;

class TeacherController extends Controller
{
    // list 
    public function list()
    {
        $teachers = Teacher::where('superAdmin', '=', 0)->get();

        return response()->json([
            'status' => 1,
            'msg' => 'List of teachers',
            'data' => $teachers
        ], 200);
    }

    // profile 
    public function profile($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);

            return response()->json([
                "status" => 1,
                "msg" => "Teacher profile",
                "data" => $teacher
            ], 200);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                "status" => 0,
                "msg" => "Student not found"
            ], 404);
        }
    }

    // register 
    public function register(Request $request)
    {
        try {
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
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                "status" => 0,
                "msg" => "Register data incorrect"
            ], 400);
        }
    }

    // delete 
    public function delete($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);

            $teacher->delete();

            return response()->json([
                "status" => 1,
                "msg" => "Teacher deleted"
            ], 200);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                "status" => 0,
                "msg" => "Student not found"
            ], 404);
        }
    }

    // update 
    public function update(Request $request, $id)
    {
        try {
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
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                "status" => 0,
                "msg" => "Student not found"
            ], 404);
        }
    }

    // list 
    public function listAll()
    {
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
