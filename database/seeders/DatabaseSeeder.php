<?php

namespace Database\Seeders;

use App\Models\payment;
use App\Models\plan;
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
        // plan::factory(3)->create();
        // payment::factory(3)->create();

        $this->call([
            RoleSeeder::class,
            CategorySeeders::class,
            userSeeder::class,
            CourseSeeders::class,
            MaterialSeeders::class,
            SubMaterialSeeders::class,
            quizSeeder::class,
            jurusanSeeder::class,
            instansiSeeder::class,
            planSeeders::class,
            paymentSeeders::class,
        ]);

    }
}
