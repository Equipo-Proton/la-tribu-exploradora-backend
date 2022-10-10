<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // game - permission function
    public function changePlayPermission(Request $request)
    {
        $teacher = auth()->user();

        $students = User::where('teacher_id', '=', $teacher->id)
            ->get();

        foreach ($students as $student) {
            $student->play_permission = $request->play;

            $student->update();
        }

        return response()->json([
            'status' => 1,
            'msg' => 'Students can play',
            'data' => $students
        ], 200);
    }

    // game - get play permission
    public function getPlayPermission()
    {
        $student = auth()->user();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the play value of the student',
            'data' => $student->play_permission
        ]);
    }

    // game - get correction
    public function getCorrection()
    {
        $student = auth()->user();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the play value of the student',
            'data' => $student->correction
        ]);
    }

    // game - send word function
    public function sendWord(Request $request)
    {
        $student = auth()->user();

        $student->word = $request->word;

        $student->update();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the word',
            'data' => $student->word
        ]);
    }

    // game - set word to null function
    public function wordNull(Request $request)
    {
        $teacher = auth()->user();

        $students = User::where('teacher_id', '=', $teacher->id)
            ->get();

        foreach ($students as $student) {
            $student->word = $request->word;

            $student->update();
        }

        return response()->json([
            'status' => 1,
            'msg' => 'This is the word',
            'data' => $students
        ]);
    }

    // game - set student id word to null function
    public function wordStudentNull(Request $request, $id)
    {
        $teacher = auth()->user();

        $student = User::where('teacher_id', '=', $teacher->id)
            ->find($id);


        $student->word = $request->word;

        $student->update();


        return response()->json([
            'status' => 1,
            'msg' => 'This is the word',
            'data' => $student
        ]);
    }

    // game - send correction 
    public function sendCorrection(Request $request, $id)
    {
        $teacher = auth()->user();

        $student = User::where('teacher_id', '=', $teacher->id)
            ->findOrFail($id);

        $student->correction = $request->correct;

        $student->update();

        return response()->json([
            'status' => 1,
            'msg' => 'User correction sent',
            'data' => $student
        ], 200);
    }

    // game - set correction to null function
    public function correctionNull(Request $request)
    {
        $student = auth()->user();

        $student->correction = $request->correction;

        $student->update();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the correction',
            'data' => $student
        ]);
    }

    public function show(Request $request, $id)
    {
        $teacher = auth()->user();

        $student = User::where('teacher_id', '=', $teacher->id)
            ->findOrFail($id);

        $student->show = $request->show;

        $student->update();

        return response()->json([
            'status' => 1,
            'msg' => 'User show',
            'data' => $student
        ], 200);
    }

    public function getShow()
    {
        $student = auth()->user();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the play value of the student',
            'data' => $student->show
        ]);
    }
}
