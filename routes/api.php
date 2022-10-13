<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserController::class, 'login'])->name('login');

// students http requests
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/logout', [UserController::class, 'logout'])->name('logout')->middleware('isadmin');
    Route::get('/student/list', [UserController::class, 'list'])->name('listStudents')->middleware('isadmin');
    Route::get('/student/profile/{id}', [UserController::class, 'profile'])->name('profileStudent')->middleware('isadmin');
    Route::post('/student/register', [UserController::class, 'register'])->name('registerStudent')->middleware('isadmin');
    Route::delete('/student/delete/{id}', [UserController::class, 'delete'])->name('deleteStudent')->middleware('isadmin');
    Route::patch('/student/update/{id}', [UserController::class, 'update'])->name('updateStudent')->middleware('isadmin');
});

// teachers http requests
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/teacher/list', [TeacherController::class, 'list'])->name('listTeachers')->middleware('superadmin');
    Route::get('/teacher/profile/{id}', [TeacherController::class, 'profile'])->name('profileTeacher')->middleware('superadmin');
    Route::post('/teacher/register', [TeacherController::class, 'register'])->name('registerTeacher')->middleware('superadmin');
    Route::delete('/teacher/delete/{id}', [TeacherController::class, 'delete'])->name('deleteTeacher')->middleware('superadmin');
    Route::patch('/teacher/update/{id}', [TeacherController::class, 'update'])->name('updateTeacher')->middleware('superadmin');
    Route::get('/listall', [TeacherController::class, 'listAll'])->name('listAll')->middleware('superadmin');
});

// game http routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    // permission to play
    Route::patch('/game/changepermission', [GameController::class, 'changePlayPermission'])->name('changePermission')->middleware('isadmin');
    Route::get('/game/getpermission', [GameController::class, 'getPlayPermission'])->name('getPermission');

    // send word / asnwer
    Route::patch('/game/sendword', [GameController::class, 'sendWord'])->name('sendWord');
    Route::patch('/game/wordnull', [GameController::class, 'wordNull'])->name('wordNull')->middleware('isadmin');
    Route::patch('/game/wordnull/{id}', [GameController::class, 'wordStudentNull'])->name('wordStudentNull')->middleware('isadmin');
   
    // send correction to the student
    Route::patch('/game/sendcorrection/{id}', [GameController::class, 'sendCorrection'])->name('sendCorrection')->middleware('isadmin');
    Route::get('/game/getcorrection', [GameController::class, 'getCorrection'])->name('getCorrection');
    Route::patch('/game/correctionnull', [GameController::class, 'correctionNull'])->name('correctionNull');

    // show correction to the student
    Route::patch('/game/show', [GameController::class, 'show'])->name('show');
    Route::get('/game/getshow', [GameController::class, 'getShow'])->name('getShow')->name('getShow');
});
