<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function teacherRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $newTeacher = new User();

        $newTeacher->name = $request->name;
        $newTeacher->email = $request->email;
        $newTeacher->password = Hash::make($request->password);
        $newTeacher->isAdmin = true;
        $newTeacher->superAdmin = false;

        $newTeacher->save();

        return response()->json([
            'status' => 1,
            'msg' => 'Teacher created',
            'data' =>  $newTeacher
        ], 200);
    }

    public function listTeachers()
    {
        $teachers = User::all()
            ->where('isAdmin', '=', 1)
            ->where('superAdmin', '=', 0);

        return response()->json([
            'status' => 1,
            'msg' => 'This is the list of teachers',
            'data' => $teachers
        ], 200);
    }

    public function listUsers()
    {
        $users = User::all()
            ->where('superAdmin', '=', 0);

        return response()->json([
            'status' => 1,
            'msg' => 'This is the list of all users',
            'data' => $users
        ], 200);
    }

    public function profile($id) {
        $user = User::where('superAdmin', '=', 0)
            ->findOrFail($id);

        return response()->json([
            'status' => 1,
            'msg' => 'This is the user',
            'data' => $user
        ]);
    }

    public function deleteTeacher($id)
    {
        $teacher = User::where('isAdmin', '=', 1)
            ->where('superAdmin', '=', 0)
            ->findOrFail($id);

        $teacher->delete();

        return response()->json([
            "status" => 1,
            "msg" => "Teacher successfully deleted"
        ], 200);
    }

    public function updateTeacher(Request $request, $id)
    {
        $user = User::where('isAdmin', '=', 1)
            ->where('superAdmin', '=', 0)
            ->findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->update();

        return response()->json([
            'status' => 1,
            'msg' => 'Teacher updated and you have logged out',
            'data' => $user
        ], 200);
    }
}
