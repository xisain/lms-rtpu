<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\material;
use App\Models\submaterial;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course = course::with('material','material.submaterial')->get();
        return view('admin.course.index', ['course'=> $course]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = category::all();
        return view('admin.course.create',[
            'categories'=> $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
        $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'nama_course' => 'required|string|max:255',
        'description' => 'required|string',
        'isLimitedCourse' => 'required|boolean',
        'start_date' => 'nullable|required_if:isLimitedCourse,1|date',
        'end_date' => 'nullable|required_if:isLimitedCourse,1|date|after:start_date',
        'maxEnrollment' => 'nullable|required_if:isLimitedCourse,1|integer|min:1',
        'public' => 'boolean',

        // Validasi materials
        'materials' => 'required|array|min:1',
        'materials.*.nama_materi' => 'required|string|max:255',

        // Validasi submaterials
        'materials.*.submaterials' => 'required|array|min:1',
        'materials.*.submaterials.*.nama_submateri' => 'required|string|max:255',
        'materials.*.submaterials.*.type' => 'required|in:text,video,pdf',
        'materials.*.submaterials.*.isi_materi' => 'required|string',
    ]);
    // dd($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $course = course::where('slugs', $slug)->with('material')->first();
        return view('course.show', ['courseData'=>$course]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(course $course)
    {
        //
    }
    public function showCourse(){
        $course = course::where('public', true)->get();
        return view('course.index',[
            'course' => $course,
        ]);
    }
}
