<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\material;
use App\Models\submaterial;
use Illuminate\Support\Str;
use App\Models\enrollment;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course = course::with('material', 'material.submaterial')->get();
        return view('admin.course.index', ['course' => $course]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = category::all();
        return view('admin.course.create', [
            'categories' => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_course' => 'required|string|max:255',
            'description' => 'required|string',
            'isLimitedCourse' => 'boolean', // tidak wajib di isi
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
        $course = course::create([
            'category_id' => $validated['category_id'],
            'nama_course' => $validated['nama_course'],
            'slugs' => Str::slug($validated['nama_course']),
            'description' => $validated['description'],
            'isLimitedCourse' => $validated['isLimitedCourse'] ?? false,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'maxEnrollment' => $validated['maxEnrollment'] ?? null,
            'public' => $validated['public'] ?? false,
        ]);


        foreach ($validated['materials'] as $mat) {
            $material = material::create([
                'course_id' => $course->id,
                'nama_materi' => $mat['nama_materi'],
            ]);

            foreach ($mat['submaterials'] as $sub) {
                submaterial::create([
                    'material_id' => $material->id,
                    'nama_submateri' => $sub['nama_submateri'],
                    'type' => $sub['type'],
                    'isi_materi' => $sub['isi_materi'],
                ]);
            }
        }

        return redirect()->route('admin.course.index')
            ->with('success', 'Course berhasil dibuat beserta material dan submaterialnya!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $course = course::where('slugs', $slug)->with('material')->first();
        $isEnrolled = false;

        if (Auth::check()) {
            $isEnrolled = enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->exists();
        }
        $firstMaterial = $course->material->first();
        $firstSubmaterial = $firstMaterial?->submaterial->first();
        return view('course.show', [
            'courseData' => $course,
            'isEnrolled' => $isEnrolled,
            'firstMaterial'=>$firstMaterial,
            'firstSubmaterial'=>$firstSubmaterial]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $course = course::with('material', 'category', 'material.submaterial')->findOrFail($id);
        return view('admin.course.edit', ['course' => $course]);
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
    public function showCourse()
    {
        $course = course::where('public', true)->get();
        return view('course.index', [
            'course' => $course,
        ]);
    }
    public function mulai($slug, $material = null, $submaterial = null)
    {
        $course = course::with('material.submaterial')
            ->where('slugs', $slug)
            ->firstOrFail();

        // Ambil materi
        $materi = $material
            ? material::where('id', $material)
            ->where('course_id', $course->id)
            ->firstOrFail()
            : $course->material->first();

        // Ambil submateri
        $submateri = $submaterial
            ? submaterial::where('id', $submaterial)
            ->where('material_id', $materi->id)
            ->firstOrFail()
            : ($materi ? $materi->submaterial->first() : null);

        if (!$materi || !$submateri) {
            return redirect()->route('course.show', $slug)
                ->with('message', 'Materi atau submateri belum tersedia.');
        }

        return view('course.view', compact('course', 'materi', 'submateri'));
    }
}
