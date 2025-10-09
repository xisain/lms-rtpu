<?php

namespace App\Http\Controllers;

use App\Models\enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\course;
use SweetAlert2\Laravel\Swal;
class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

   public function store($slug)
    {
        $course = course::where('slugs', $slug)->firstOrFail();
        $userId = Auth::id();

        // Cek apakah sudah terdaftar
        $existing = Enrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return back()->with('message', 'Kamu sudah terdaftar di course ini.');
        }

        Enrollment::create([
            'user_id' => $userId,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        Swal::success([
            'title' => 'Berhasil!',
            'text' => 'Kamu berhasil mendaftar ke course "' . $course->nama_course . '"',
        ]);

        return back();
    }


}
