<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            SubMaterialSeeders::class
        ]);
        User::factory()->create([
            'name' => 'admin',
            'category_id'=> 1,
            'roles_id'=> 1,
            'email' => 'admin@gmail.com',
        ]);
    }
}
