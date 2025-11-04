<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCertificateJob;
use App\Models\category;
use App\Models\Certificate;
use App\Models\course;
use App\Models\enrollment;
use App\Models\material;
use App\Models\progress;
use App\Models\Quiz;
use App\Models\quiz_attempt;
use App\Models\quiz_option;
use App\Models\quiz_question;
use App\Models\submaterial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SweetAlert2\Laravel\Swal;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role->name == 'admin') {
            $course = course::with('material', 'material.submaterial')->orderBy('id', 'desc')->paginate(5);

            return view('admin.course.index', ['course' => $course]);
        } else {
            $course = course::with('material', 'material.submaterial')->where('teacher_id', '=', Auth::user()->id)->get();

            return view('dosen.course.index', ['course' => $course]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = category::all();
        $teacher = User::with('role')->whereHas('role', function ($q) {
            $q->where('id', 2);
        })->get();

        return view('admin.course.create', [
            'categories' => $category,
            'teachers' => $teacher,
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
            'image_link' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'isLimitedCourse' => 'boolean',
            'start_date' => 'nullable|required_if:isLimitedCourse,1|date',
            'end_date' => 'nullable|required_if:isLimitedCourse,1|date|after:start_date',
            'maxEnrollment' => 'nullable|required_if:isLimitedCourse,1|integer|min:1',
            'public' => 'boolean',
            'teacher_id' => 'required|exists:users,id',

            // Validasi materials
            'materials' => 'required|array|min:1',
            'materials.*.nama_materi' => 'required|string|max:255',
            'materials.*.quiz.judul_quiz' => 'nullable|string|max:255',
            'materials.*.quiz.questions' => 'nullable|array',
            'materials.*.quiz.questions.*.pertanyaan' => 'nullable|string',
            'materials.*.quiz.questions.*.options' => 'nullable|array',
            'materials.*.quiz.questions.*.correct_option' => 'nullable|integer|min:0|max:3',

            // Validasi submaterials
            'materials.*.submaterials' => 'required|array|min:1',
            'materials.*.submaterials.*.nama_submateri' => 'required|string|max:255',
            'materials.*.submaterials.*.type' => 'required|in:text,video,pdf',
            'materials.*.submaterials.*.isi_materi' => 'required_if:materials.*.submaterials.*.type,text,video|nullable',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_link')) {
            $imagePath = $request->file('image_link')->store('course/images', 'public');
        }

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
            'image_link' => $imagePath,
            'teacher_id' => $validated['teacher_id'],
        ]);

        foreach ($validated['materials'] as $materialIndex => $mat) {
            $material = material::create([
                'course_id' => $course->id,
                'nama_materi' => $mat['nama_materi'],
            ]);

            if (isset($mat['submaterials']) && is_array($mat['submaterials'])) {
                foreach ($mat['submaterials'] as $submaterialIndex => $sub) {
                    $content = null;

                    if ($sub['type'] === 'text') {
                        $content = $sub['isi_materi'] ?? '';
                    } elseif ($sub['type'] === 'video') {
                        $content = $sub['isi_materi'] ?? '';
                    } elseif ($sub['type'] === 'pdf') {
                        $fileKey = "materials.{$materialIndex}.submaterials.{$submaterialIndex}.isi_materi";

                        if ($request->hasFile($fileKey)) {
                            $pdfPath = $request->file($fileKey)->store('course/pdf', 'public');
                            $content = $pdfPath;
                        }
                    }

                    submaterial::create([
                        'material_id' => $material->id,
                        'nama_submateri' => $sub['nama_submateri'],
                        'type' => $sub['type'],
                        'isi_materi' => $content,
                    ]);
                }
            }

            // Handle Quiz
            if (isset($mat['quiz']['judul_quiz']) && ! empty($mat['quiz']['judul_quiz']) && isset($mat['quiz']['questions']) && count($mat['quiz']['questions']) > 0) {
                $quiz = quiz::create([
                    'material_id' => $material->id,
                    'judul_quiz' => $mat['quiz']['judul_quiz'],
                    'is_required' => true,
                ]);

                foreach ($mat['quiz']['questions'] as $q) {
                    if (empty($q['pertanyaan'])) {
                        continue;
                    }

                    $question = quiz_question::create([
                        'quiz_id' => $quiz->id,
                        'pertanyaan' => $q['pertanyaan'],
                    ]);

                    if (isset($q['options']) && is_array($q['options'])) {
                        foreach ($q['options'] as $index => $optionText) {
                            if (empty($optionText)) {
                                continue;
                            }

                            quiz_option::create([
                                'quiz_question_id' => $question->id,
                                'teks_pilihan' => $optionText,
                                'is_correct' => isset($q['correct_option']) && $index === (int) $q['correct_option'],
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.course.index')
            ->with('success', 'Course berhasil dibuat beserta material dan submaterialnya!');
    }

    public function manageCourse($id){
        $course = course::with(['material','material.submaterial', 'material.quiz'])->find($id);
        // $course = course::find($id);
        $enrollmentUser = enrollment::where('course_id',$course->id)->with('user')->paginate(10);
        return view('admin.course.manageCourse',
        [
        'course'=> $course,
        'enrolluser' =>$enrollmentUser]);
    }
    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $course = course::where('slugs', $slug)
            ->with(['material.submaterial'])
            ->first();
        $courseEnroll = $course->maxSlotEnrollment();
        $courseStudent = $course->countEnrollment();
        $courseExpire = $course->expireCourse();
        // Course bukan public dan bukan admin
        if (! $course->public && auth()->user()->role->id != 1) {
            Swal::error([
                'title' => 'Error',
                'text' => 'Kelas Tidak Di temukan',
            ]);

            return redirect()->route('course.index');
        }
        if (! $course) {
            Swal::error([
                'title' => 'Error',
                'text' => 'Kelas Tidak Di temukan',
            ]);

            return redirect()->route('course.index');
        }

        Log::info('Course ditemukan:', ['id' => $course->id, 'slug' => $slug]);

        $isEnrolled = false;

        if (Auth::check()) {
            $isEnrolled = enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->exists();
        }

        Log::info('Status enrollment:', ['isEnrolled' => $isEnrolled]);

        $firstMaterial = $course->material->first();
        $firstSubmaterial = $firstMaterial?->submaterial->first();

        Log::info('First material:', [
            'id' => $firstMaterial?->id,
            'name' => $firstMaterial?->title,
            'firstSubmaterial' => $firstSubmaterial?->title,
        ]);

        $previewSubmaterial = null;

        if (! $isEnrolled) {
            Log::info('User belum enroll, mencari preview submaterial...');

            // Cari previewSubmaterial tanpa peduli enroll
            foreach ($course->material as $material) {
                $textSubmaterial = $material->submaterial->where('type', 'text')->first();
                if ($textSubmaterial) {
                    $previewSubmaterial = $textSubmaterial;
                    break;
                }
            }

            if (! $previewSubmaterial) {
                foreach ($course->material as $material) {
                    $firstSub = $material->submaterial->first();
                    if ($firstSub) {
                        $previewSubmaterial = $firstSub;
                        break;
                    }
                }
            }
        }

        Log::info('Preview submaterial akhir:', [
            'id' => $previewSubmaterial?->id,
            'title' => $previewSubmaterial?->title,
            'type' => $previewSubmaterial?->type,
        ]);

        $certificateStatus = null;
        if ($isEnrolled) {
            // Cek apakah semua materi sudah selesai
            $allCompleted = true;
            foreach ($course->material as $m) {
                // Cek submaterial completion
                foreach ($m->submaterial as $sub) {
                    $progress = progress::where('user_id', Auth::id())
                        ->where('submaterial_id', $sub->id)
                        ->where('status', 'completed')
                        ->exists();

                    if (! $progress) {
                        $allCompleted = false;
                        break 2;
                    }
                }

                // Cek quiz completion jika ada quiz
                if ($m->quiz) {
                    $quizAttempt = quiz_attempt::where('user_id', Auth::id())
                        ->where('quiz_id', $m->quiz->id)
                        ->where('status', 'completed')
                        ->where('score', '>=', 70)
                        ->exists();

                    if (! $quizAttempt) {
                        $allCompleted = false;
                        break;
                    }
                }
            }

            if ($allCompleted) {
                // Cek status sertifikat
                $certificate = Certificate::where('user_id', Auth::id())
                    ->where('course_id', $course->id)
                    ->first();

                $certificateStatus = [
                    'completed' => true,
                    'certificate' => $certificate,
                ];
            } else {
                $certificateStatus = [
                    'completed' => false,
                    'certificate' => null,
                ];
            }
        }

        return view('course.show', [
            'courseData' => $course,
            'courseMaxEnroll' => $courseEnroll,
            'expireCourse' => $courseExpire,
            'countEnroll' => $courseStudent,
            'isEnrolled' => $isEnrolled,
            'firstMaterial' => $firstMaterial,
            'firstSubmaterial' => $firstSubmaterial,
            'previewSubmaterial' => $previewSubmaterial,
            'certificateStatus' => $certificateStatus,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $course = Course::with('material.submaterial', 'material.quiz.questions.options')->findOrFail($id);

        // Check if user has permission to edit this course
        if (auth()->user()->role->id == 2 && $course->teacher_id != auth()->id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit course ini.');
        }

        if (auth()->user()->role->id == 1) {
            // Admin view
            $category = Category::all();
            $teachers = User::whereHas('role', fn ($q) => $q->where('name', 'dosen'))->get();

            return view('admin.course.edit', [
                'course' => $course,
                'categories' => $category,
                'teachers' => $teachers,
            ]);
        } else {
            // Dosen view
            $category = Category::all();

            return view('dosen.course.edit', [
                'course' => $course,
                'categories' => $category,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Fix: Update tanpa menghapus id lama
        $course = Course::findOrFail($id);
        if (Auth::user()->role->id == 2 && $course->teacher_id != Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit course ini.');
        }

        // Base validation rules
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'nama_course' => 'required|string|max:255',
            'description' => 'required|string',
            'image_link' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'isLimitedCourse' => 'boolean',
            'start_date' => 'nullable|required_if:isLimitedCourse,1|date',
            'end_date' => 'nullable|required_if:isLimitedCourse,1|date|after:start_date',
            'maxEnrollment' => 'nullable|required_if:isLimitedCourse,1|integer|min:1',
            'public' => 'boolean',
            'materials' => 'required|array|min:1',
            'materials.*.id' => 'nullable|integer|exists:materials,id',
            'materials.*.nama_materi' => 'required|string|max:255',
            'materials.*.quiz.id' => 'nullable|integer|exists:quizzes,id',
            'materials.*.quiz.judul_quiz' => 'nullable|string|max:255',
            'materials.*.quiz.questions' => 'nullable|array',
            'materials.*.quiz.questions.*.id' => 'nullable|integer|exists:quiz_questions,id',
            'materials.*.quiz.questions.*.pertanyaan' => 'nullable|string',
            'materials.*.quiz.questions.*.options' => 'nullable|array',
            'materials.*.quiz.questions.*.options_ids' => 'nullable|array',
            'materials.*.quiz.questions.*.correct_option' => 'nullable|integer|min:0|max:3',
            'materials.*.submaterials' => 'required|array|min:1',
            'materials.*.submaterials.*.id' => 'nullable|integer|exists:submaterials,id',
            'materials.*.submaterials.*.nama_submateri' => 'required|string|max:255',
            'materials.*.submaterials.*.type' => 'required|in:text,video,pdf',
            'materials.*.submaterials.*.isi_materi' => 'nullable',
        ];

        if (auth()->user()->role->id == 1) {
            $rules['teacher_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);
        try {
            DB::beginTransaction();

            // 1. Update course basic info
            $courseData = [
                'category_id' => $validated['category_id'],
                'nama_course' => $validated['nama_course'],
                'slugs' => Str::slug($validated['nama_course']),
                'description' => $validated['description'],
                'isLimitedCourse' => $validated['isLimitedCourse'] ?? false,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'maxEnrollment' => $validated['maxEnrollment'] ?? null,
                'public' => $validated['public'] ?? false,
            ];

            if (auth()->user()->role->id == 1 && isset($validated['teacher_id'])) {
                $courseData['teacher_id'] = $validated['teacher_id'];
            }

            $course->update($courseData);

            // 2. Handle Untuk Bagian Image pada Course
            if ($request->hasFile('image_link')) {
                $oldImage = $course->image_link;
                $newImage = $request->file('image_link')->store('course/images', 'public');
                $course->update(['image_link' => $newImage]);

                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // 3.  Proses Update Material, Submaterial dan Quiz
            $existingMaterialIds = $course->material()->pluck('id')->toArray();
            $updatedMaterialIds = [];

            foreach ($validated['materials'] as $materialIndex => $mat) {
                // Cek apakah ada ID (existing) atau tidak (new)
                $materialId = $mat['id'] ?? null;

                Log::info("Processing Material #{$materialIndex}", [
                    'has_id' => ! empty($materialId),
                    'id_value' => $materialId,
                    'nama_materi' => $mat['nama_materi'],
                ]);

                // Create atau update material
                if ($materialId && in_array($materialId, $existingMaterialIds)) {
                    // UPDATE material yang sudah ada
                    $material = Material::findOrFail($materialId);
                    $material->update([
                        'nama_materi' => $mat['nama_materi'],
                    ]);
                } else {
                    // CREATE material baru
                    $material = $course->material()->create([
                        'nama_materi' => $mat['nama_materi'],
                    ]);
                }

                $updatedMaterialIds[] = $material->id;

                // Handle submaterials
                if (isset($mat['submaterials']) && is_array($mat['submaterials'])) {
                    $existingSubmaterialIds = $material->submaterial()->pluck('id')->toArray();
                    $updatedSubmaterialIds = [];

                    foreach ($mat['submaterials'] as $subIndex => $sub) {
                        $submaterialId = $sub['id'] ?? null;
                        $content = $sub['isi_materi'] ?? null;

                        Log::info("Processing Submaterial #{$subIndex}", [
                            'material_id' => $material->id,
                            'has_id' => ! empty($submaterialId),
                            'id_value' => $submaterialId,
                            'type' => $sub['type'],
                        ]);

                        // Handle PDF type
                        if ($sub['type'] === 'pdf') {
                            $pdfFieldName = "materials.{$materialIndex}.submaterials.{$subIndex}.isi_materi";

                            if ($request->hasFile($pdfFieldName)) {
                                // Delete old PDF if exists
                                if ($submaterialId && ! empty($content)) {
                                    Storage::disk('public')->delete($content);
                                }
                                // Store new file
                                $content = $request->file($pdfFieldName)->store('course/pdf', 'public');
                            } elseif (! empty($sub['isi_materi'])) {
                                // Keep existing file
                                $content = $sub['isi_materi'];
                            } elseif (! $submaterialId) {
                                // New submaterial requires a file
                                continue;
                            }
                        }

                        // Skip if no content for required types
                        if ($content === null && in_array($sub['type'], ['text', 'video'])) {
                            continue;
                        }

                        // Create or update submaterial
                        if ($submaterialId && in_array($submaterialId, $existingSubmaterialIds)) {
                            // UPDATE existing submaterial
                            $submaterial = Submaterial::findOrFail($submaterialId);
                            $submaterial->update([
                                'nama_submateri' => $sub['nama_submateri'],
                                'type' => $sub['type'],
                                'isi_materi' => $content,
                            ]);
                        } else {
                            // CREATE new submaterial
                            $submaterial = $material->submaterial()->create([
                                'nama_submateri' => $sub['nama_submateri'],
                                'type' => $sub['type'],
                                'isi_materi' => $content,
                            ]);
                        }

                        $updatedSubmaterialIds[] = $submaterial->id;
                    }

                    // Delete removed submaterials
                    $deletedSubmaterials = array_diff($existingSubmaterialIds, $updatedSubmaterialIds);
                    if (! empty($deletedSubmaterials)) {
                        // Delete files first
                        $toDeleteSubs = Submaterial::whereIn('id', $deletedSubmaterials)->get();
                        foreach ($toDeleteSubs as $sub) {
                            if ($sub->type === 'pdf' && ! empty($sub->isi_materi)) {
                                Storage::disk('public')->delete($sub->isi_materi);
                            }
                        }
                        // Then delete records
                        Submaterial::whereIn('id', $deletedSubmaterials)->delete();
                    }
                }

                // Handle quiz
                if (isset($mat['quiz']['judul_quiz']) && ! empty($mat['quiz']['judul_quiz'])) {
                    $quizId = $mat['quiz']['id'] ?? null;

                    if ($quizId) {
                        // UPDATE existing quiz
                        $quiz = Quiz::findOrFail($quizId);
                        $quiz->update([
                            'judul_quiz' => $mat['quiz']['judul_quiz'],
                            'is_required' => true,
                        ]);
                    } else {
                        // CREATE new quiz
                        $quiz = $material->quiz()->create([
                            'judul_quiz' => $mat['quiz']['judul_quiz'],
                            'is_required' => true,
                        ]);
                    }

                    if (isset($mat['quiz']['questions'])) {
                        $existingQuestionIds = $quiz->questions()->pluck('id')->toArray();
                        $updatedQuestionIds = [];

                        foreach ($mat['quiz']['questions'] as $q) {
                            $questionId = $q['id'] ?? null;

                            if ($questionId && in_array($questionId, $existingQuestionIds)) {
                                // UPDATE existing question
                                $question = quiz_question::findOrFail($questionId);
                                $question->update(['pertanyaan' => $q['pertanyaan']]);
                            } else {
                                // CREATE new question
                                $question = $quiz->questions()->create([
                                    'pertanyaan' => $q['pertanyaan'],
                                ]);
                            }

                            $updatedQuestionIds[] = $question->id;

                            // Handle options
                            if (isset($q['options'])) {
                                // Delete all old options and create new ones
                                $question->options()->delete();

                                foreach ($q['options'] as $index => $optionText) {
                                    if (! empty($optionText)) {
                                        $question->options()->create([
                                            'teks_pilihan' => $optionText,
                                            'is_correct' => $index === (int) ($q['correct_option'] ?? -1),
                                        ]);
                                    }
                                }
                            }
                        }

                        // Delete removed questions
                        $deletedQuestions = array_diff($existingQuestionIds, $updatedQuestionIds);
                        if (! empty($deletedQuestions)) {
                            quiz_question::whereIn('id', $deletedQuestions)->delete();
                        }
                    }
                } else {
                    // Delete quiz if checkbox unchecked
                    $material->quiz()->delete();
                }
            }

            // Delete removed materials
            $deletedMaterials = array_diff($existingMaterialIds, $updatedMaterialIds);
            if (! empty($deletedMaterials)) {
                Material::whereIn('id', $deletedMaterials)->delete();
            }

            DB::commit();

            $redirectRoute = Auth::user()->role->id == 1 ? 'admin.course.index' : 'dosen.course.index';

            return redirect()->route($redirectRoute)
                ->with('success', 'Course berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course Update Error: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal mengupdate course: '.$e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        //    $deletedCourse = course::with(['material', 'material.submaterial','material.quiz', 'material.quiz.questions','material.quiz.questions.options'])->findOrFail($id);
        $deletedCourse = Course::findOrFail($id);
        $deletedCourse->delete();
        $redirectRoute = Auth::user()->role->id == 1 ? 'admin.course.index' : 'dosen.course.index';

        return redirect()->route($redirectRoute);
    }

    public function showCourse(Request $request)
    {
        $query = Course::where('public', true);

        // Apply filters if present
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_course', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $course = $query->get();
        $categories = Category::all();

        return view('course.index', [
            'course' => $course,
            'categories' => $categories,
        ]);
    }

    public function filterCourse(Request $request)
    {
        $query = Course::where('public', true);

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_course', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $course = $query->get();

        // Return partial view for AJAX request
        return view('course.partials.course-cards', compact('course'));
    }

    public function myCourse(Request $request)
    {
        $userId = auth()->user()->id;

        $query = Course::where('public', true);

        // Apply filters if present
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_course', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        $course = Course::whereHas('enrollment', function($q) use ($userId) {
        $q->where('user_id', $userId);
        })->with('enrollment')->get();
        $categories = Category::all();

        return view('course.index', [
            'course' => $course,
            'categories' => $categories,
        ]);
    }

    public function guestDaftarKelas()
    {
        $course = course::where('public', true)->get();
        $categories = category::all();

        return view('course.index', [
            'categories' => $categories,
            'course' => $course,
        ]);
    }

    public function mulai($slug, $material = null, $submaterial = null)
    {
        $course = course::with(['material.submaterial', 'material.quiz.questions.options'])
            ->where('slugs', $slug)
            ->firstOrFail();

        // Cek apakah user sudah enroll di course ini
        if (! enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists()) {
            return redirect()->route('course.show', $slug)
                ->with('error', 'Anda harus enroll terlebih dahulu untuk mengakses materi.');
        }

        // Ambil materi
        $materi = $material
            ? material::where('id', $material)
                ->where('course_id', $course->id)
                ->firstOrFail()
            : $course->material->first();
        $isQuiz = $submaterial === 'quiz';

        if ($isQuiz) {
            // Cek apakah semua submateri sudah selesai
            if (! $materi->isAllSubmaterialCompleted(Auth::id())) {
                return redirect()->route('course.show', $slug)
                    ->with('error', 'Selesaikan semua submateri terlebih dahulu untuk mengakses quiz.');
            }

            $quiz = $materi->quiz;
            if (! $quiz) {
                return redirect()->route('course.show', $slug)
                    ->with('error', 'Quiz belum tersedia untuk materi ini.');
            }

            // Ambil attempt terakhir jika ada
            $lastAttempt = $quiz->getLastAttempt(Auth::id());

            return view('course.quiz', compact('course', 'materi', 'quiz', 'lastAttempt'));
        }

        // Ambil submateri
        $submateri = $submaterial
            ? submaterial::where('id', $submaterial)
                ->where('material_id', $materi->id)
                ->firstOrFail()
            : ($materi ? $materi->submaterial->first() : null);

        if (! $materi || ! $submateri) {
            return redirect()->route('course.show', $slug)
                ->with('error', 'Materi atau submateri belum tersedia.');
        }

        // Cek apakah user bisa mengakses submateri ini (sudah selesai materi sebelumnya)
        if (! progress::canAccess(Auth::id(), $submateri->id)) {
            return redirect()->route('course.show', $slug)
                ->with('error', 'Anda harus menyelesaikan materi sebelumnya terlebih dahulu.');
        }

        // Track progress otomatis saat mengakses
        progress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'submaterial_id' => $submateri->id,
            ],
            ['status' => 'completed']
        );

        // Cek apakah semua materi dan quiz sudah selesai
        $allCompleted = true;
        foreach ($course->material as $m) {
            // Cek submaterial completion
            foreach ($m->submaterial as $sub) {
                $progress = progress::where('user_id', Auth::id())
                    ->where('submaterial_id', $sub->id)
                    ->where('status', 'completed')
                    ->exists();

                if (! $progress) {
                    $allCompleted = false;
                    break 2;
                }
            }

            // Cek quiz completion jika ada quiz
            if ($m->quiz) {
                $quizAttempt = quiz_attempt::where('user_id', Auth::id())
                    ->where('quiz_id', $m->quiz->id)
                    ->where('status', 'completed')
                    ->where('score', '>=', 70) // Nilai minimum untuk lulus
                    ->exists();

                if (! $quizAttempt) {
                    $allCompleted = false;
                    break;
                }
            }
        }

        // Jika semua materi selesai, generate sertifikat
        if ($allCompleted) {
            $existingCertificate = Certificate::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();

            if (! $existingCertificate) {
                // Generate sertifikat
                $certificateNumber = 'CERT-'.Str::upper(Str::random(8));
                $certificate = Certificate::create([
                    'user_id' => Auth::id(),
                    'course_id' => $course->id,
                    'certificate_number' => $certificateNumber,
                    'issued_date' => now(),
                ]);

                // Generate PDF di background
                GenerateCertificateJob::dispatch(Auth::user(), $course, $certificate);
            }
        }

        return view('course.view', compact('course', 'materi', 'submateri'));
    }

    public function quizSubmit(Request $request, string $slug, int $material)
    {
        $course = Course::where('slugs', $slug)->firstOrFail();
        $materi = Material::with('quiz.questions.options')->findOrFail($material);
        $quiz = $materi->quiz;

        if (! $quiz) {
            return redirect()->back()->with('error', 'Quiz tidak ditemukan.');
        }

        // Validate if user has access to this quiz
        if (! $quiz->canAccess($request->user())) {
            return redirect()->back()->with('error', 'Anda belum dapat mengakses quiz ini.');
        }

        // Validate answers
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:quiz_options,id',
        ]);

        try {
            DB::beginTransaction();
            // Create new attempt
            $attempt = new quiz_attempt;
            $attempt->user_id = $request->user()->id;
            $attempt->quiz_id = $quiz->id;

            // Calculate score
            $totalQuestions = $quiz->questions->count();
            $correctAnswers = 0;
            $answersDetail = [];

            foreach ($request->answers as $questionId => $optionId) {
                $question = $quiz->questions->find($questionId);
                $selectedOption = quiz_option::find($optionId);
                $correctOption = $question->options->where('is_correct', true)->first();

                $isCorrect = $selectedOption && $selectedOption->is_correct;

                if ($isCorrect) {
                    $correctAnswers++;
                }

                // Store answer detail
                $answersDetail[] = [
                    'question_id' => $questionId,
                    'question_text' => $question->pertanyaan,
                    'selected_option_id' => $optionId,
                    'selected_option_text' => $selectedOption->teks_pilihan,
                    'correct_option_id' => $correctOption->id,
                    'correct_option_text' => $correctOption->teks_pilihan,
                    'is_correct' => $isCorrect,
                ];
            }

            $score = ($correctAnswers / $totalQuestions) * 100;
            $attempt->score = round($score, 2);
            $attempt->answers = json_encode($answersDetail);
            $attempt->status = 'completed';
            $attempt->save();

            DB::commit();

            return redirect()->back()->with('success', 'Quiz berhasil diselesaikan!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan jawaban quiz.');
        }
    }
}
