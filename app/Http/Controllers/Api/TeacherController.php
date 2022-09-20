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
            ->where('isAdmin', '=', 1);

        return response()->json([
            'status' => 1,
            'msg' => 'This is the list of teachers',
            'data' => $teachers
        ], 200);
    }

    public function teacherProfile($id)
    {
        $teacher = User::findOrFail($id);

        return response()->json([
            "status" => 1,
            "msg" => "This is the user profile",
            "data" => $teacher
        ], 200);
    }

    public function deleteTeacher($id)
    {
        $teacher = User::findOrFail($id);

        $teacher->delete();

        return response()->json([
            "status" => 1,
            "msg" => "Teacher successfully deleted"
        ], 200);
    }

    public function update(Request $request, $id)
    {
        /*   $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]); */

        $teacher = User::findOrFail($id);

        $currentDirector = auth()->user();

        if($currentDirector->id != $teacher->id) {
            $teacher->name = $request->name;
            $teacher->email = $request->email;
            $teacher->password = Hash::make($request->password);
    
            $teacher->save();

           /*  $currentUser->tokens()->delete(); */

            return response()->json([
                'status' => 1,
                'msg' => 'Teacher updated and you have logged out',
                'data' => $teacher
            ], 200);
        }

        return response()->json([
            'status' => 0,
            'msg' => 'You cannot update yourself',
        ]);
    }
}
