<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected routes here
    Route::prefix('course')->group(function (){
        Route::get('/', [CourseController::class, 'showCourse'])->name('index')->name('course.index');
        Route::get('{slug}', [CourseController::class, 'show'])->name('course.show');

    });
});

Route::get('/', function () {
    return view('welcome');
});
