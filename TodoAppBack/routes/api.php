<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;

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

Route::middleware(['verified.email'])->group(function () {
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/forgot-password', [UserController::class, 'forgotPassword'])->name('password.forgot');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.reset');
Route::post('/verify-email', [UserController::class, 'verifyCode']);

// Protected routes (requires Sanctum authentication and email verification)
Route::middleware(['auth:sanctum', 'verified.email'])->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::apiResource('todos', TodoController::class);
    Route::post('/logout', [UserController::class, 'logout']);
});
