<?php

namespace App\Http\Controllers;

use App\Models\final_task;
use Illuminate\Http\Request;
use App\Models\course;
use App\Models\final_task_review;
use App\Models\final_task_submission;

class FinalTaskController extends Controller
{

    public function index()
    {
        $reviewer_id = auth()->user()->id;
        $courseWithReview = Course::where('reviewer_id', $reviewer_id)->get();
        return view("dosen.review.index", compact("courseWithReview"));
    }
    public function listFinalTask($slugs)
    {
        $course = Course::where("slugs", $slugs)->first();
        $taskId  = final_task::where("course_id", $course->id)->first()->id;
        $taskList = final_task_submission::where("final_task_id", $taskId)->with('user')->get();
        return view('dosen.review.list', compact('taskList', "course"));
    }
    public function reviewTask($slugs, $idSubmission)
    {
        $course = Course::where("slugs", $slugs)->first();
        $submission = final_task_submission::with('user')->find($idSubmission);
        return view('dosen.review.review', compact('submission','course'));
    }
    public function approvalTask(Request $request, $courseSlug, $idSubmission){
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

        // Simpan review (tanpa status)
        $review = final_task_review::create($reviewData);

        // Update status di submission
        $submission = final_task_submission::findOrFail($validated['final_task_submission_id']);
        $submission->update(['status' => $submissionStatus]);

        return redirect()->back()->with('success','Berhasil Menilai Peserta');

    }
    public function viewTask($slug)
    {
        $course = Course::where("slugs", $slug)->firstOrFail();

        $finalTask = final_task::where('course_id', $course->id)->firstOrFail();
        $submission = final_task_submission::where('final_task_id',$finalTask->id)->first();
        return view("course.final_task", compact('finalTask', 'course', 'submission'));
    }
    public function submitTask(Request $request)
    {
        $validated = $request->validate([
            'link_google_drive' => 'required|string',
            'agreement' => 'required'
        ], [
            'link_google_drive' => 'Harap isi Link Google Drive Dengan Benar',
            'agreement' => 'Tolong Ceklis Agreement Terlebih dahulu',
        ]);

        $finalTask = final_task_submission::create([
            'user_id' => $request->user_id,
            'final_task_id' => $request->final_task_id,
            'link_google_drive' => $validated['link_google_drive'],
            'status' => 'submitted'
        ]);
        return redirect()->back()->with('success', 'Berhasil Mengumpulkan Final task');
    }
}
