<?php

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

Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/register', [UserController::class, 'register'])->middleware('isadmin');
    Route::get('/users', [UserController::class, 'getUsers'])->name('users')->middleware('isadmin');
    Route::get('/userprofile/{id}', [UserController::class, 'userProfile'])->middleware('isadmin');
    Route::get('/logout', [UserController::class, 'logout']);
    Route::patch('/update/{id}', [UserController::class, 'update'])->name('update')->middleware('isadmin');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->middleware('isadmin');
});
