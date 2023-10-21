<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Message Call Api
Route::get('/message', [MessageController::class, 'message']);

// User Login Or Register
Route::post('/user/login', [AuthController::class, 'login']);
Route::post('/user/add', [AuthController::class, 'store']);

// User List Api
Route::get('/users', [UserController::class, 'index']);

// Authenticated User Apis
Route::middleware('auth:api')->group(function () {
    Route::put('/user/edit/{id}', [UserController::class, 'update']);
    Route::delete('/user/delete/{id}', [UserController::class, 'destroy']);
});


