<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\course;
use App\Models\material;

class MaterialSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = course::all();

        foreach ($courses as $course) {
            $materials = [
                ['nama_materi' => 'Pengenalan ' . $course->nama_course, 'course_id' => $course->id],
                ['nama_materi' => 'Konsep Dasar ' . $course->nama_course, 'course_id' => $course->id],
                ['nama_materi' => 'Implementasi ' . $course->nama_course, 'course_id' => $course->id],
            ];

        material::insert($materials);
    }
    }
}
