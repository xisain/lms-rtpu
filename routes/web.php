<?php

use App\Http\Controllers\adminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Middleware\adminMiddleware;
use App\Http\Middleware\dosenMiddleware;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/daftar-kelas',[CourseController::class,'guestDaftarKelas'])->name('list.kelas');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected routes here
    Route::prefix('course')->group(function () {
        Route::get('/', [CourseController::class, 'showCourse'])->name('course.index');
        Route::get('{slug}', [CourseController::class, 'show'])->name('course.show');
        Route::get('{slug}/{material?}/{submaterial?}', [CourseController::class, 'mulai'])->name('course.mulai');
        Route::post('{slug}/enroll', [EnrollmentController::class, 'store'])->name('course.enroll');
        // Buat quiz nanti jangan di apa apain 
        Route::post('{slug}/{material}/quiz/submit',function(){
            return "test";
        });
    });

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::post('/sidebar/toggle', function(Illuminate\Http\Request $request) {
    $request->session()->put('sidebar_open', $request->input('open'));
    return response()->json(['success' => true]);
})->name('sidebar.toggle');
        Route::get('/', [adminController::class, 'index'])->name('admin.home');

        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.category.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('admin.category.create');
            Route::post('/', [CategoryController::class, 'store'])->name('admin.category.store');
            Route::get('/edit/{category}', [CategoryController::class, 'edit'])->name('admin.category.edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
            Route::get('/create', [UserController::class, 'create'])->name('admin.user.create');
            Route::get('/edit/{user}', [UserController::class, 'edit'])->name('admin.user.edit');
            Route::put('/edit/{user}/update', [UserController::class, 'update'])->name('admin.user.update');
        });
        Route::prefix('course')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('admin.course.index');
            Route::get('/create', [CourseController::class, 'create'])->name('course.create');
            Route::post('/', [CourseController::class, 'store'])->name('course.store');
            Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');
            Route::post('/{id}', [CourseController::class, 'destroy'])->name('course.destroy');
        });
    });

    Route::prefix('dosen')->middleware(dosenMiddleware::class)->group(function () {
        Route::prefix('course')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('admin.course.index');
            Route::post('/', [CourseController::class, 'store'])->name('course.store');
            Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');
        });
    });
});

Route::get('/', function () {
    return view('index');
})->name('home');


Route::get('views/course/view', function () {
    return view('course/view');
})->name('view');

