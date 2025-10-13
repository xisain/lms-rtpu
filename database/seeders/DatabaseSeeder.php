<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            CategorySeeders::class,
            CourseSeeders::class,
            MaterialSeeders::class,
            SubMaterialSeeders::class,
            quizSeeder::class
        ]);
        User::factory()->create([
            'name' => 'admin',
            'category_id'=> 1,
            'roles_id'=> 1,
            'isActive' => true,
            'password'=> Hash::make('admin123'),
            'email' => 'admin@gmail.com',
        ]);
         User::factory()->create([
            'name' => 'student1',
            'category_id'=> 1,
            'roles_id'=> 3,
            'isActive' => true,
            'password'=> Hash::make('student1'),
            'email' => 'student1@gmail.com',
        ]);
         User::factory()->create([
            'name' => 'student2',
            'category_id'=> 1,
            'roles_id'=> 3,
            'isActive' => true,
            'password'=> Hash::make('student2'),
            'email' => 'student2@gmail.com',
        ]);
         User::factory()->create([
            'name' => 'dosen',
            'category_id'=> 1,
            'roles_id'=> 2,
            'isActive' => true,
            'password'=> Hash::make('dosen123'),
            'email' => 'dosen@gmail.com',
        ]);
        user::factory(20)->create();
    }
}
