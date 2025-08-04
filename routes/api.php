<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/admin/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', function (Request $request) {
    return $request->user()->tokens()->delete();
})->middleware('auth:sanctum');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum', AdminMiddleware::class)->group(function () {
    Route::get('/admin', function (Request $request) {
        return $request->user();
    });

});



Route::middleware('auth:sanctum')->group(function () {
    
});

Route::get('/forgot-password', [AuthController::class, 'forgot_passwpord'])->name('forgot-password');
Route::get('/reset-password', [AuthController::class, 'reset_passwpord'])->name('reset-password');