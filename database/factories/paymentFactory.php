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
            'id' => $this->faker->unique()->numberBetween(1, 999999999999999),
            'nama' => fake()->name(1),
            'account_number' => $this->faker->unique()->numberBetween(1000000000, 9999999999),
            'account_name' => fake()->name(),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
