<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CourseAPI;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('/course',CoureAPI::class);
Route::middleware(['throttle:api'])->group(function(){
Route::get('/course',[CourseAPI::class, 'index']);
});
