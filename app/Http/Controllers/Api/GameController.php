<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class GameController extends Controller
{
    // game permission
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

    // get play permission
    public function getPlayPermission()
    {
        $student = auth()->user();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the play value of the student',
            'data' => $student->play_permission
        ]);
    }

    // get correction
    public function getCorrection()
    {
        $student = auth()->user();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the play value of the student',
            'data' => $student->correction
        ]);
    }

    // send correction 
    public function sendCorrection(Request $request, $id)
    {
        try {
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
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                "status" => 0,
                "msg" => "Student not found"
            ], 404);
        }
    }

    // set correction to null 
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

    // send word function
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

    // set word to null function
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

    // set student word to null 
    public function wordStudentNull(Request $request, $id)
    {
        try {
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
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                "status" => 0,
                "msg" => "Student not found"
            ], 404);
        }
    }

    // change the show word in the database
    public function show(Request $request)
    {
        $teacher = auth()->user();

        $students = $teacher->users;

        foreach ($students as $student) {
            $student->show = $request->show;

            $student->update();
        }

        return response()->json([
            'status' => 1,
            'msg' => 'User show',
            'data' => $students
        ], 200);
    }

    // get the show word 
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
