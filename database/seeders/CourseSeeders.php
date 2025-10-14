<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\course;
use App\Models\category;
use Illuminate\Support\Carbon;
class CourseSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = category::get()->first()->id;
        course::create([
            'category_id' => $category,
            'nama_course' => 'Course testing',
            'image_link'=> 'course\images\course-test.png',
            'description' => 'test description',
            'slugs' => 'course-testing',
            'isLimitedCourse' => 1,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),
            'maxEnrollment'=> 100,
            'public'=> 1,
            'teacher_id'=> 1
        ]);
    }
}
