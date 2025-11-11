<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\payment>
 */
class paymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->numberBetween(1, 1000),
            'nama' => fake()->text(),
            'account_number' => fake()->text(),
            'account_name' => fake()->name(),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
