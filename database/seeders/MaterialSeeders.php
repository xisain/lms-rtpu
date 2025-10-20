<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Material;

class MaterialSeeders extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            $materials = [
                [
                    'nama_materi' => 'Pengenalan ' . $course->nama_course,
                    'course_id' => $course->id,
                ],
                [
                    'nama_materi' => 'Konsep Dasar ' . $course->nama_course,
                    'course_id' => $course->id,
                ],
                [
                    'nama_materi' => 'Implementasi ' . $course->nama_course,
                    'course_id' => $course->id,
                ],
                [
                    'nama_materi' => 'Studi Kasus ' . $course->nama_course,
                    'course_id' => $course->id,
                ],
            ];

            Material::insert($materials);
        }
    }
}
