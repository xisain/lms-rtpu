<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\plan;

class planSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic Plan',
                'description' => 'Plan ini cocok untuk pemula.',
                'price' => 100000,
                'features' => 'Akses terbatas, Support email',
                'course' => json_encode(["1"]), // course ID 1
                'duration_in_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Standard Plan',
                'description' => 'Plan menengah dengan fitur lebih lengkap.',
                'price' => 250000,
                'features' => 'Akses semua course, Support chat',
                'course' => json_encode(["1", "2"]), // course ID 1 & 2
                'duration_in_days' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Plan',
                'description' => 'Plan lengkap untuk semua course.',
                'price' => 500000,
                'features' => 'Akses semua course, Prioritas support, Sertifikat',
                'course' => json_encode(["1", "2", "3"]), // course ID 1, 2, 3
                'duration_in_days' => 90,
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
