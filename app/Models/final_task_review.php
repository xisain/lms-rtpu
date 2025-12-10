<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class final_task_review extends Model
{
    protected $fillable = [
        'final_task_id',
        'final_task_submission_id',
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
        'catatan'
    ];

    public function finalTask() {
        return $this->belongsTo(final_task::class);
    }
    public function submission()
    {
        return $this->belongsTo(final_task_submission::class, 'final_task_submission_id');
    }

}
