<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\plan>
 */
class planFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(1),
            'description' => $this->faker->sentence(3),
            'price' => $this->faker->randomFloat(2, 50000, 500000), // harga dalam rupiah
            'duration_in_days' => $this->faker->numberBetween(7, 365),

            // Fitur contoh
            'features' => json_encode($this->faker->randomElements([
                'Akses semua kursus',
                'Konsultasi mentor 24 jam',
                'Sertifikat kelulusan',
                'Materi dapat diunduh',
                'Akses komunitas eksklusif',
            ], $this->faker->numberBetween(2, 5))),

            // Course hanya dari ID 1–3
            'course' => array_slice([1,2,3], $this->faker->numberBetween(0, 2)),


            'is_active' => $this->faker->boolean(),
        ];
    }
}
