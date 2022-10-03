<?php

use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ruta login sin middlewares
Route::post('/login', [UserController::class, 'login'])->name('login');

// Rutas del profesor admin
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/playvalue', [UserController::class, 'getPlayvalue'])->name('playValue');
    Route::patch('/play', [UserController::class, 'play'])->name('play')->middleware('isadmin');
    Route::post('/register', [UserController::class, 'register'])->name('register')->middleware('isadmin');
    Route::get('/users', [UserController::class, 'getUsers'])->name('users')->middleware('isadmin');
    Route::get('/userprofile/{id}', [UserController::class, 'userProfile'])->name('userProfile')->middleware('isadmin');
    Route::get('/logout', [UserController::class, 'logout']);
    Route::patch('/update/{id}', [UserController::class, 'update'])->name('update')->middleware('isadmin');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('deleteUser')->middleware('isadmin');
});

// Rutas del director superadmin
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/teacher/register', [TeacherController::class, 'teacherRegister'])->name('teacherRegister')->middleware('superadmin');
    Route::get('/teachers', [TeacherController::class, 'listTeachers'])->name('teachers')->middleware('superadmin');
    Route::get('/profile/{id}', [TeacherController::class, 'profile'])->name('profile')->middleware('superadmin');
    Route::patch('/teacher/update/{id}', [TeacherController::class, 'updateTeacher'])->name('updateTeacher')->middleware('superadmin');
    Route::delete('/teacher/delete/{id}', [TeacherController::class, 'deleteTeacher'])->name('deleteTeacher')->middleware('superadmin');
    Route::get('/listusers', [TeacherController::class, 'listUsers'])->middleware('superadmin');
});

