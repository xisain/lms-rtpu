<?php

use App\Http\Controllers\adminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\adminMiddleware;
use App\Http\Middleware\courseMiddleware;
use App\Http\Middleware\dosenMiddleware;
use App\Models\subscription;

// Unguarded Route
Route::get('certificate/{certificate}/download', [CertificateController::class, 'download'])->name('certificate.download');
Route::get('/filter', [CourseController::class, 'filterCourse'])->name('course.filter');
Route::get('/plan',[SubscriptionController::class,'viewPlan'])->name('plan');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forget-password',[AuthController::class,'showForgetPassword'])->name('forgetpassword');
    Route::post('/forget-password',[AuthController::class,'forgetPassword'])->name('forgetpassword.request');
    Route::get('/reset-password/{token}/',[AuthController::class,'showResetPassword'])->name('resetpassword');
    Route::post('/reset-password/',[AuthController::class,'resetPassword'])->name('resetpassword.store');
    Route::get('/daftar-kelas', [CourseController::class, 'guestDaftarKelas'])->name('list.kelas');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('plan')->group(function(){

    Route::get('{id}/checkout',[SubscriptionController::class,'checkout'])->name('plan.checkout');
    Route::post('/',[SubscriptionController::class,'purchases'])->name('plan.purchases');

    });
    // Protected routes here
    Route::prefix('course')->group(function () {
        Route::get('my',[CourseController::class,'myCourse'])->name('course.my');
        Route::get('/', [CourseController::class, 'showCourse'])->name('course.index');

        Route::get('{slug}', [CourseController::class, 'show'])->name('course.show');
        Route::get('{slug}/{material?}/{submaterial?}', [CourseController::class, 'mulai'])->name('course.mulai');
        Route::post('{slug}/enroll', [EnrollmentController::class, 'store'])->name('course.enroll');
        // Buat quiz nanti jangan di apa apain
        Route::post('{slug}/{material}/quiz/submit', [CourseController::class, 'quizSubmit'])->name('quiz.submit');

        // Certificate routes
        Route::post('{course}/certificate/{user}', [CertificateController::class, 'generate'])->name('certificate.generate');
        Route::get('certificate/{certificate}/download', [CertificateController::class, 'download'])->name('certificate.download');
        Route::get('certificate/{certificate}/status', [CertificateController::class, 'status'])->name('certificate.status');
    });

    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::post('/sidebar/toggle', function (Illuminate\Http\Request $request) {
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
            // Route::post('/', [UserController::class, 'index'])->name('admin.user.store');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
            Route::get('/create', [UserController::class, 'createBulkUser'])->name('admin.user.create');
            Route::get('/edit/{user}', [UserController::class, 'edit'])->name('admin.user.edit');
            Route::put('/edit/{user}/update', [UserController::class, 'update'])->name('admin.user.update');
            Route::post('/bulk/store', [UserController::class, 'storeBulkUser'])->name('admin.user.bulk.store');
            Route::get('/bulk/template', [UserController::class, 'downloadTemplate'])->name('admin.user.bulk.template');
        });
        Route::prefix('course')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('admin.course.index');
            Route::get('/create', [CourseController::class, 'create'])->name('course.create');
            Route::post('/', [CourseController::class, 'store'])->name('course.store');
            Route::put('/edit/{id}/update', [CourseController::class, 'update'])->name('course.update');
            Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');
            Route::delete('/{id}', [CourseController::class, 'destroy'])->name('course.destroy');
            Route::get('/{id}/manage-course', [CourseController::class, 'manageCourse'])->name('course.manage');
        });
        Route::prefix('plan')->group(function () {
            Route::get('/', [SubscriptionController::class, 'index'])->name('admin.plan.index');
            Route::post('/', [SubscriptionController::class, 'store'])->name('admin.plan.store');
            Route::get('create', [SubscriptionController::class, 'create'])->name('admin.plan.create');
            Route::get('edit/{subs}', [SubscriptionController::class, 'edit'])->name('admin.plan.edit');
            Route::put('update/{plan}', [SubscriptionController::class, 'update'])->name('admin.plan.update');
            Route::delete('{subs}/delete', [SubscriptionController::class, 'destroy'])->name('admin.plan.destroy');
        });
        Route::prefix('transaction')->group(function (){
            Route::get('/',[SubscriptionController::class,'transactionTable'])->name('admin.transaction.index');
            Route::put('{id}/approval',[SubscriptionController::class,'approval'])->name('admin.transaction.approval');
        });
        Route::prefix('payment')->group( function(){
            Route::get('/', [PaymentController::class, 'index'])->name('admin.payment.index');
            Route::get('/create', [PaymentController::class, 'create'])->name('admin.payment.create');
            Route::get('/edit/{id}', [PaymentController::class, 'edit'])->name('admin.payment.edit');
            Route::post('/', [PaymentController::class, 'store'])->name('admin.payment.store');
            Route::put('{id}/update', [PaymentController::class, 'update'])->name('admin.payment.update');
            Route::delete('/{id}', [PaymentController::class, 'destroy'])->name('admin.payment.destroy');
        });
    });

    Route::prefix('dosen')->middleware(dosenMiddleware::class)->group(function () {
        Route::get('/', function () {
            return view('dosen.dosen');
        })->name('dosen.home');
        Route::prefix('course')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('dosen.course.index');
            Route::put('edit/{id}/update', [CourseController::class, 'update'])->name('dosen.course.update');
            Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('dosen.course.edit');
        });
    });
});

Route::get('/', function () {
    return view('index');
})->name('home');


Route::get('views/course/view', function () {
    return view('course/view');
})->name('view');
