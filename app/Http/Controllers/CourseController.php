<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\material;
use App\Models\submaterial;
use App\Models\progress;
use Illuminate\Support\Str;
use App\Models\enrollment;
use App\Models\Quiz;
use App\Models\quiz_attempt;
use App\Models\quiz_option;
use App\Models\quiz_question;
use App\Models\QuizAttempt;
use App\Models\QuizOption;
use Illuminate\Support\Facades\DB;
use App\Models\User;


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
        $teacher = User::with('role')->whereHas('role', function ($q) {
            $q->where('id', 2);
        })->get();
        return view('admin.course.create', [
            'categories' => $category,
            'teachers' => $teacher
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

            // Validasi submaterials - PERBAIKAN DI SINI
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

            // PERBAIKAN: Cek apakah submaterials ada
            if (isset($mat['submaterials']) && is_array($mat['submaterials'])) {
                foreach ($mat['submaterials'] as $submaterialIndex => $sub) {
                    $content = null;

                    // Tentukan isi_materi berdasarkan tipe
                    if ($sub['type'] === 'text') {
                        $content = $sub['isi_materi'] ?? '';
                    } elseif ($sub['type'] === 'video') {
                        $content = $sub['isi_materi'] ?? '';
                    } elseif ($sub['type'] === 'pdf') {
                        // PERBAIKAN: Gunakan Request untuk mengakses file
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

            // Handle Quiz (tetap sama)
            if (
                isset($mat['quiz']['judul_quiz']) &&
                !empty($mat['quiz']['judul_quiz']) &&
                isset($mat['quiz']['questions']) &&
                count($mat['quiz']['questions']) > 0
            ) {
                $quiz = quiz::create([
                    'material_id' => $material->id,
                    'judul_quiz' => $mat['quiz']['judul_quiz'],
                    'is_required' => true
                ]);

                foreach ($mat['quiz']['questions'] as $q) {
                    if (empty($q['pertanyaan'])) continue;

                    $question = quiz_question::create([
                        'quiz_id' => $quiz->id,
                        'pertanyaan' => $q['pertanyaan']
                    ]);

                    if (isset($q['options']) && is_array($q['options'])) {
                        foreach ($q['options'] as $index => $optionText) {
                            if (empty($optionText)) continue;

                            quiz_option::create([
                                'quiz_question_id' => $question->id,
                                'teks_pilihan' => $optionText,
                                'is_correct' => isset($q['correct_option']) && $index === (int) $q['correct_option']
                            ]);
                        }
                    }
                }
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
        $course = course::where('slugs', $slug)
            ->with(['material.submaterial'])
            ->first();


        $isEnrolled = false;

        if (Auth::check()) {
            $isEnrolled = enrollment::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->exists();
        }

        $firstMaterial = $course->material->first();
        $firstSubmaterial = $firstMaterial?->submaterial->first();

        // Ambil submaterial text pertama untuk preview
        $previewSubmaterial = null;
        if (!$isEnrolled) {
            foreach ($course->material as $material) {
                $textSubmaterial = $material->submaterial->where('type', 'text')->first();
                if ($textSubmaterial) {
                    $previewSubmaterial = $textSubmaterial;
                    break;
                }
            }
        }

        return view('course.show', [
            'courseData' => $course,
            'isEnrolled' => $isEnrolled,
            'firstMaterial' => $firstMaterial,
            'firstSubmaterial' => $firstSubmaterial,
            'previewSubmaterial' => $previewSubmaterial
        ]);
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
    public function guestDaftarKelas()
    {
        $course = course::where('public', true)->get();
        return view('course.index', [
            'course' => $course,
        ]);
    }
    public function mulai($slug, $material = null, $submaterial = null)
    {
        $course = course::with(['material.submaterial', 'material.quiz.questions.options'])
            ->where('slugs', $slug)
            ->firstOrFail();

        // Cek apakah user sudah enroll di course ini
        if (!enrollment::where('user_id', Auth::id())
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
            if (!$materi->isAllSubmaterialCompleted(Auth::id())) {
                return redirect()->route('course.show', $slug)
                    ->with('error', 'Selesaikan semua submateri terlebih dahulu untuk mengakses quiz.');
            }

            $quiz = $materi->quiz;
            if (!$quiz) {
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

        if (!$materi || !$submateri) {
            return redirect()->route('course.show', $slug)
                ->with('error', 'Materi atau submateri belum tersedia.');
        }

        // Cek apakah user bisa mengakses submateri ini (sudah selesai materi sebelumnya)
        if (!progress::canAccess(Auth::id(), $submateri->id)) {
            return redirect()->route('course.show', $slug)
                ->with('error', 'Anda harus menyelesaikan materi sebelumnya terlebih dahulu.');
        }

        // Track progress otomatis saat mengakses
        progress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'submaterial_id' => $submateri->id
            ],
            ['status' => 'completed']
        );

        return view('course.view', compact('course', 'materi', 'submateri'));
    }

    public function quizSubmit(Request $request, string $slug, int $material,)
    {
        $course = Course::where('slugs', $slug)->firstOrFail();
        $materi = Material::with('quiz.questions.options')->findOrFail($material);
        $quiz = $materi->quiz;

        if (!$quiz) {
            return redirect()->back()->with('error', 'Quiz tidak ditemukan.');
        }

        // Validate if user has access to this quiz
        if (!$quiz->canAccess($request->user())) {
            return redirect()->back()->with('error', 'Anda belum dapat mengakses quiz ini.');
        }

        // Validate answers
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:quiz_options,id'
        ]);

        try {
            DB::beginTransaction();
            // Create new attempt
            $attempt = new quiz_attempt();
            $attempt->user_id = $request->user()->id;
            $attempt->quiz_id = $quiz->id;
            // dd($attempt);

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
                    'is_correct' => $isCorrect
                ];
            }
            // dd(json_encode($answersDetail));
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
