<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;

Route::get('/landingpage', function () {
    return view('index');
});

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
        Route::get('/', [CourseController::class, 'showCourse'])->name('course.index');
        Route::get('{slug}', [CourseController::class, 'show'])->name('course.show');
        Route::post('{slug}/enroll' , function ($slug){
            return "Enroll course: " . $slug;
        })->name('course.enroll');
        Route::post('/',[CourseController::class,'store'])->name('course.store');
    });
    Route::prefix('admin')->group(function (){
        Route::prefix('category')->group(function(){

        });
        Route::prefix('user')->group(function(){

        });
        Route::prefix('course')->group(function(){
        Route::get('/',[CourseController::class, 'index']);
        Route::get('/create',[CourseController::class, 'create'])->name('course.create');
        Route::get('/edit/{}',[CourseController::class, 'edit'])->name('course.edit');
        Route::post('/{id}',[CourseController::class, 'destroy'])->name('course.destroy');
        });
    });

     Route::prefix('dosen')->group(function (){
        Route::prefix('course')->group(function(){
        // Include Material, Submaterial
        });
    });

});

Route::get('/',[CourseController::class, 'showCourse']);


Route::get('views/course/view', function () {
    return view('course/view');
})->name('view');