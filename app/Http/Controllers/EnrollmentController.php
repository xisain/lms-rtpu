<?php

namespace App\Http\Controllers;

use App\Mail\enrollCourse;
use App\Models\course;
use App\Models\enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
        $user = User::where('id', $userId)->firstOrFail();
        if ($course->expireCourse()) {
            Swal::error([
                'title' => 'Gagal',
                'text' => 'Course Ini Sudah Expire :(',
            ]);

            return back();
        }
        if ($course->maxSlotEnrollment()) {
            Swal::error([
                'title' => 'Gagal',
                'text' => 'Course Ini Sudah penuh :(',
            ]);

            return back();
        }
        // Cek apakah sudah terdaftar
        $existing = enrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return back()->with('message', 'Kamu sudah terdaftar di course ini.');
        }

        enrollment::create([
            'user_id' => $userId,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        Mail::to($user->email)->send(new enrollCourse($course, $user->name,));

        Swal::success([
            'title' => 'Berhasil!',
            'text' => 'Kamu berhasil mendaftar ke course "'.$course->nama_course.'", Silahkan Join Group Whatsapp Agar Memudahkan ada jika ada pertanyaan ataupun kendala',
        ]);

        return back();
    }
}
