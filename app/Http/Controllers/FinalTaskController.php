<?php

namespace App\Http\Controllers;

use App\Models\course;
use App\Models\final_task;
use App\Models\final_task_review;
use App\Models\final_task_submission;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FinalTaskController extends Controller
{
    public function exportPDF($slugs)
    {
        $course = Course::where('slugs', $slugs)->firstOrFail();
        $taskId = final_task::where('course_id', $course->id)->first()->id;

        // Ambil semua submission dengan review
        $taskList = final_task_submission::where('final_task_id', $taskId)
            ->with(['user', 'review'])
            ->get();

        $reviewItems = $this->getReviewItems();

        // Load view untuk PDF dengan margin
        $pdf = PDF::loadView('dosen.review.pdf', compact('taskList', 'course', 'reviewItems'))
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 15)       // 15mm margin atas
            ->setOption('margin-right', 10)     // 10mm margin kanan
            ->setOption('margin-bottom', 15)    // 15mm margin bawah
            ->setOption('margin-left', 10)      // 10mm margin kiri
            ->setOption('dpi', 96)              // DPI untuk kualitas gambar
            ->setOption('enable-local-file-access', true); // Akses file lokal

        // Download PDF
        return $pdf->download('review-tugas-akhir-' . $course->slugs . '-' . date('Y-m-d') . '.pdf');
    }

    // Atau jika ingin preview dulu:
    public function previewPDF($slugs)
    {
        $course = Course::where('slugs', $slugs)->firstOrFail();
        $taskId = final_task::where('course_id', $course->id)->first()->id;

        $taskList = final_task_submission::where('final_task_id', $taskId)
            ->with(['user', 'review'])
            ->get();

        $reviewItems = $this->getReviewItems();

        // Load view untuk PDF dengan margin
        $pdf = PDF::loadView('dosen.review.pdf', compact('taskList', 'course', 'reviewItems'))
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 15)       // 15mm margin atas
            ->setOption('margin-right', 10)     // 10mm margin kanan
            ->setOption('margin-bottom', 15)    // 15mm margin bawah
            ->setOption('margin-left', 10)      // 10mm margin kiri
            ->setOption('dpi', 96)              // DPI untuk kualitas gambar
            ->setOption('enable-local-file-access', true); // Akses file lokal

        // Tampilkan di browser
        return $pdf->stream('review-tugas-akhir-' . $course->slugs . '-' . date('Y-m-d') . '.pdf');
    }
    public function index()
    {
        $reviewer_id = auth()->user()->id;
        $courses = Course::where('reviewer_id', $reviewer_id)
            ->with('finalTask')
            ->get();

        return view('dosen.review.index', compact('courses'));
    }

    public function listFinalTask($slugs)
    {
        $course = Course::where('slugs', $slugs)->first();
        $taskId = final_task::where('course_id', $course->id)->first()->id;
        $taskList = final_task_submission::where('final_task_id', $taskId)->with('user')->get();

        return view('dosen.review.list', compact('taskList', 'course'));
    }

    public function reviewTask($slugs, $idSubmission)
    {
        $course = Course::where('slugs', $slugs)->first();
        $submission = final_task_submission::with('user')->find($idSubmission);

        return view('dosen.review.review', compact('submission', 'course'));
    }

    public function approvalTask(Request $request, $courseSlug, $idSubmission)
    {
        // dd($request->all());
        $submission = final_task_submission::find($idSubmission);
        $validated = $request->validate([
            // Hidden inputs
            'final_task_id' => 'required|exists:final_tasks,id',
            'final_task_submission_id' => 'required|exists:final_task_submissions,id',

            // Checklist komponen (boolean fields)
            'kurikulum_permen_39_2025' => 'nullable|boolean',
            'kurikulum_permen_3_2020' => 'nullable|boolean',
            'cpl_prodi' => 'nullable|boolean',
            'distribusi_mata_kuliah_dan_highlight' => 'nullable|boolean',
            'cpl_prodi_yang_dibebankan_pada_mata_kuliah' => 'nullable|boolean',
            'matriks_kajian' => 'nullable|boolean',
            'tujuan_belajar' => 'nullable|boolean',
            'peta_kompentensi' => 'nullable|boolean',
            'perhitungan_sks' => 'nullable|boolean',
            'scl' => 'nullable|boolean',
            'metode_case_study_dan_team_based_project' => 'nullable|boolean',
            'rps' => 'nullable|boolean',
            'rancangan_penilaian_dalam_1_semester' => 'nullable|boolean',
            'rancangan_tugas_1_pertemuan' => 'nullable|boolean',
            'instrumen_penilaian_hasil_belajar' => 'nullable|boolean',
            'rubrik_penilaian' => 'nullable|boolean',
            'rps_microteaching' => 'nullable|boolean',
            'materi_microteaching' => 'nullable|boolean',
            'penilaian_microteaching' => 'nullable|boolean',

            // Status untuk submission dan catatan untuk review
            'status' => 'required|in:approved,rejected',
            'catatan' => 'nullable|string|max:1000',
        ], [
            // Custom error messages
            'final_task_id.required' => 'ID Tugas Akhir tidak ditemukan.',
            'final_task_id.exists' => 'Tugas Akhir tidak valid.',
            'final_task_submission_id.required' => 'ID Submission tidak ditemukan.',
            'final_task_submission_id.exists' => 'Submission tidak valid.',
            'status.required' => 'Status review wajib dipilih.',
            'status.in' => 'Status harus approved atau rejected.',
            'catatan.max' => 'Catatan maksimal 1000 karakter.',
        ]);
        $checkboxFields = [
            'kurikulum_permen_39_2025',
            'kurikulum_permen_3_2020',
            'cpl_prodi',
            'distribusi_mata_kuliah_dan_highlight',
            'cpl_prodi_yang_dibebankan_pada_mata_kuliah',
            'matriks_kajian',
            'tujuan_belajar',
            'peta_kompentensi',
            'perhitungan_sks',
            'scl',
            'metode_case_study_dan_team_based_project',
            'rps',
            'rancangan_penilaian_dalam_1_semester',
            'rancangan_tugas_1_pertemuan',
            'instrumen_penilaian_hasil_belajar',
            'rubrik_penilaian',
            'rps_microteaching',
            'materi_microteaching',
            'penilaian_microteaching',
        ];

        foreach ($checkboxFields as $field) {
            $validated[$field] = $request->has($field) ? true : false;
        }

        // Pisahkan data untuk review dan submission
        $reviewData = collect($validated)->except(['status'])->toArray();
        $submissionStatus = $validated['status'];

        // Update atau create review
        $submission = final_task_submission::findOrFail($validated['final_task_submission_id']);

        if ($submission->review) {
            // Update review yang sudah ada
            $submission->review->update($reviewData);
        } else {
            // Buat review baru jika belum ada
            final_task_review::create($reviewData);
        }

        // Update status di submission
        $submission->update(['status' => $submissionStatus]);

        return redirect()->back()->with('success', 'Berhasil Menilai Peserta');

    }

    public function viewTask($slug)
    {
        $course = Course::where('slugs', $slug)->with('material.submaterial')->firstOrFail();
        $finalTask = final_task::where('course_id', $course->id)->firstOrFail();
        $submission = final_task_submission::where('final_task_id', $finalTask->id)
            ->where('user_id', auth()->id())
            ->first();

        // Calculate user progress
        $userId = auth()->id();
        $totalSubmaterials = 0;
        $completedSubmaterials = 0;

        foreach ($course->material as $material) {
            $visibleSubmaterials = $material->submaterial->where('hidden', false);
            foreach ($visibleSubmaterials as $submaterial) {
                $totalSubmaterials++;
                if (\App\Models\progress::isCompleted($userId, $submaterial->id)) {
                    $completedSubmaterials++;
                }
            }
        }

        $userProgress = $totalSubmaterials > 0 ? round(($completedSubmaterials / $totalSubmaterials) * 100) : 0;

        $statusConfig = $this->getStatusConfig();
        $reviewItems = $this->getReviewItems();

        return view('course.final_task', compact('finalTask', 'course', 'submission', 'statusConfig', 'reviewItems', 'userProgress'));
    }

    public function submitTask(Request $request)
    {
        $validated = $request->validate([
            'link_google_drive' => 'required|string',
            'agreement' => 'required',
        ], [
            'link_google_drive' => 'Harap isi Link Google Drive Dengan Benar',
            'agreement' => 'Tolong Ceklis Agreement Terlebih dahulu',
        ]);

        final_task_submission::create([
            'user_id' => $request->user_id,
            'final_task_id' => $request->final_task_id,
            'link_google_drive' => $validated['link_google_drive'],
            'status' => 'submitted',
        ]);

        return redirect()->back()->with('success', 'Berhasil Mengumpulkan Tugas Akhir');
    }

    public function resubmitTask(Request $request)
    {
        $validated = $request->validate([
            'link_google_drive' => 'required|string',
            'agreement' => 'required',
            'submission_id' => 'required|exists:final_task_submissions,id',
        ], [
            'link_google_drive' => 'Harap isi Link Google Drive Dengan Benar',
            'agreement' => 'Tolong Ceklis Agreement Terlebih dahulu',
            'submission_id' => 'ID Pengumpulan tidak ditemukan',
        ]);

        $submission = final_task_submission::findOrFail($request->submission_id);
        $review = final_task_review::where('final_task_submission_id',$submission->id)->first();
        $review->delete();
        // Pastikan submission milik user yang login
        if ($submission->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pengumpulan ini');
        }

        $submission->update([
            'link_google_drive' => $validated['link_google_drive'],
            'status' => 'submitted',
        ]);

        return redirect()->back()->with('success', 'Tugas Akhir berhasil diperbarui dan menunggu review');
    }

    private function getStatusConfig(): array
    {
        return [
            'submitted' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'clock', 'label' => 'Menunggu Review'],
            'approved' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'check-circle', 'label' => 'Disetujui'],
            'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'x-circle', 'label' => 'Ditolak'],
            'resubmmit' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'icon' => 'refresh', 'label' => 'Perlu Diubah'],
        ];
    }

    private function getReviewItems(): array
    {
        return [
            'kurikulum_permen_39_2025' => 'Kurikulum Permen 39/2025',
            'kurikulum_permen_3_2020' => 'Kurikulum Permen 3/2020',
            'cpl_prodi' => 'CPL Prodi',
            'distribusi_mata_kuliah_dan_highlight' => 'Distribusi Mata Kuliah dan Highlight',
            'cpl_prodi_yang_dibebankan_pada_mata_kuliah' => 'CPL Prodi pada Mata Kuliah',
            'matriks_kajian' => 'Matriks Kajian',
            'tujuan_belajar' => 'Tujuan Belajar',
            'peta_kompentensi' => 'Peta Kompetensi',
            'perhitungan_sks' => 'Perhitungan SKS',
            'scl' => 'SCL (Student Centered Learning)',
            'metode_case_study_dan_team_based_project' => 'Metode Case Study & Team Based Project',
            'rps' => 'RPS',
            'rancangan_penilaian_dalam_1_semester' => 'Rancangan Penilaian 1 Semester',
            'rancangan_tugas_1_pertemuan' => 'Rancangan Tugas 1 Pertemuan',
            'instrumen_penilaian_hasil_belajar' => 'Instrumen Penilaian Hasil Belajar',
            'rubrik_penilaian' => 'Rubrik Penilaian',
            'rps_microteaching' => 'RPS Microteaching',
            'materi_microteaching' => 'Materi Microteaching',
            'penilaian_microteaching' => 'Penilaian Microteaching',
        ];
    }
}
