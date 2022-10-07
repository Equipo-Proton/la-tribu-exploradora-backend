<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PlayController extends Controller
{
    // give permission to play
    public function play(Request $request) {
        $teacher = auth()->user();

        $users = User::all()
            ->where('isAdmin', '=', 0)
            ->where('superAdmin', '=', 0)
            ->where('teacher', '=', $teacher->id);
        
        foreach($users as $user) {
            $user->play = $request->play;
            
            $user->update();
        }

        return response()->json([
            'status' => 1,
            'msg' => 'Users can play',
            'data' => $users
        ], 200);
    }

    // get value permission to play
    public function getPlayValue() {
        $user = auth()->user();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the play value of the student',
            'data' => $user->play
        ]);
    }

    // send word to the teacher
    public function sendWord(Request $request) {
        $student = auth()->user();

        $student->word = $request->word;

        $student->update();

        return response()->json([
            'status' => 1,
            'msg' => 'This is the word',
            'data' => $student->word
        ]);
    }

    // send correction to the students
    public function sendCorrection(Request $request, $id) {
        $teacher = auth()->user();

        $user = User::where('isAdmin', '=', 0)
            ->where('superAdmin', '=', 0)
            ->where('teacher', '=', $teacher->id)
            ->findOrFail($id);
        
       
        $user->correct = $request->correct;
            
        $user->update();
        
        return response()->json([
            'status' => 1,
            'msg' => 'User correction sent',
            'data' => $user
        ], 200);
    }
}
