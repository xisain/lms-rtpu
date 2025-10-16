<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
