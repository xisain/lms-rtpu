<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('final_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->string('instruksi');
            $table->timestamps();
        });
        Schema::create('final_task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('final_task_id')->references('id')->on('final_tasks');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('link_google_drive');
            $table->enum('status',['submitted','approved','rejected','resubmmit'])->default('submitted');
            $table->timestamps();
        });
        Schema::create('final_task_review', function (Blueprint $table) {
            $table->id();

            // Relasi ke tugas akhir dan submission
            $table->foreignId('final_task_id')
                  ->constrained('final_tasks')
                  ->cascadeOnDelete();

            $table->foreignId('final_task_submission_id')
                  ->constrained('final_task_submissions')
                  ->cascadeOnDelete();


            $table->boolean('kurikulum_permen_39_2025')->default(false);
            $table->boolean('kurikulum_permen_3_2020')->default(false);


            $table->boolean('cpl_prodi')->default(false);
            $table->boolean('distribusi_mata_kuliah_dan_highlight')->default(false);
            $table->boolean('cpl_prodi_yang_dibebankan_pada_mata_kuliah')->default(false);
            $table->boolean('matriks_kajian')->default(false);
            $table->boolean('tujuan_belajar')->default(false);
            $table->boolean('peta_kompentensi')->default(false);
            $table->boolean('perhitungan_sks')->default(false);
            $table->boolean('scl')->default(false);
            $table->boolean('metode_case_study_dan_team_based_project')->default(false);
            $table->boolean('rps')->default(false);
            $table->boolean('rancangan_penilaian_dalam_1_semester')->default(false);
            $table->boolean('rancangan_tugas_1_pertemuan')->default(false);
            $table->boolean('instrumen_penilaian_hasil_belajar')->default(false);
            $table->boolean('rubrik_penilaian')->default(false);
            $table->boolean('rps_microteaching')->default(false);
            $table->boolean('materi_microteaching')->default(false);
            $table->boolean('penilaian_microteaching')->default(false);
            $table->string('catatan')->nullable();

            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_tasks');
        Schema::dropIfExists('final_task_submission');
        Schema::dropIfExists('final_task_review');
    }
};
