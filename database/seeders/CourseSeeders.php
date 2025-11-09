<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\course;
use App\Models\category;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CourseSeeders extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::pluck('id')->toArray();

        $courses = [
            [
                'nama_course' => 'Belajar Dasar Pemrograman Web',
                'image_link' => 'course/images/web-dasar.png',
                'description' => 'Pelajari dasar-dasar HTML, CSS, dan JavaScript untuk membangun website interaktif.',
                'isLimitedCourse' => 0,
                'maxEnrollment' => 0,
                'public' => 1,
                'teacher_id' => 1,
            ],
            [
                'nama_course' => 'Pemrograman Python untuk Pemula',
                'image_link' => 'course/images/python.png',
                'description' => 'Kursus pengantar Python untuk membangun logika pemrograman yang kuat.',
                'isLimitedCourse' => 1,
                'maxEnrollment' => 100,
                'public' => 1,
                'teacher_id' => 1,
            ],
            [
                'nama_course' => 'Pengantar Machine Learning',
                'image_link' => 'course/images/ml.png',
                'description' => 'Pahami konsep dan penerapan dasar Machine Learning menggunakan Python dan scikit-learn.',
                'isLimitedCourse' => 1,
                'maxEnrollment' => 50,
                'public' => 1,
                'teacher_id' => 2,
            ],
        ];

        foreach ($courses as $course) {
            Course::create([
                'category_id' => $categoryIds[array_rand($categoryIds)],
                'nama_course' => $course['nama_course'],
                'image_link' => $course['image_link'],
                'description' => $course['description'],
                'slugs' => Str::slug($course['nama_course']),
                'isLimitedCourse' => $course['isLimitedCourse'],
                'start_date' => Carbon::now()->subDays(rand(0, 10)),
                'end_date' => Carbon::now()->addDays(rand(10, 60)),
                'maxEnrollment' => $course['maxEnrollment'],
                'public' => $course['public'],
                'teacher_id' => $course['teacher_id'],
            ]);
        }
    }
}
