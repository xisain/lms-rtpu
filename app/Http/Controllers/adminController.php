<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\course;
use App\Models\category;
use App\Models\enrollment;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class adminController extends Controller
{
    public function index()
    {

        // Total Users
        $totalUsers = User::count();

        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->count();

        $totalCourses = Course::count();

        $activeCourses = Course::where('public', true)->count();


        $totalCategories = Category::count();


        $categoriesWithCourses = Category::has('course')->count();


        $totalEnrollments = Enrollment::count();

        $recentActivities = Enrollment::with(['user', 'course'])->latest()->take(10)->get();

        $popularCourses = Course::withCount('enrollment')
                                ->with('category')
                                ->orderBy('enrollment_count', 'desc')
                                ->take(5)
                                ->get();



        $topCategories = Category::withCount('course')
                                 ->orderBy('course_count', 'desc')
                                 ->take(5)
                                 ->get();



        $recentUsers = User::withCount('enrollment')
                           ->latest()
                           ->take(5)
                           ->get();
        $userNeedActivate = User::where('isActive', false)->count();

        return view('admin.admin', compact(
            'totalUsers',
            'newUsersThisMonth',
            'totalCourses',
            'activeCourses',
            'totalCategories',
            'categoriesWithCourses',
            'recentActivities',
            'totalEnrollments',
            'popularCourses',
            'topCategories',
            'recentUsers',
            'userNeedActivate'
        ));
    }
    public function exportCoursePDF($slugs){
    $course = Course::where('slugs',$slugs)
        ->with('material','material.submaterial','material.quiz','material.quiz.questions',
               'enrollment', 'enrollment.user.progress', 'enrollment.user.progress.submaterial',
               'finalTask', 'finalTask.submission.review')
        ->first();

    $pdf = PDF::loadView('admin.course.pdf', compact('course'))->setPaper('a4','landscape');
    return $pdf->download('course-'.$slugs.'.pdf');
}
}
