<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\subscription>
 */
class subscriptionFactory extends Factory
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
            'user_id' => $this->faker->numberBetween(1, 24),
            'plan_id' => $this->faker->numberBetween(1, 3),
            'payment_method_id' => $this->faker->numberBetween(1, 1000),
            'starts_at' => fake()->text(),
            'ends_at' => fake()->text(),
            'status' => fake()->text(),
            'payment_proof_link' => fake()->text(),
            'created_at' => fake()->text(),
            'updated_at' => fake()->text(),
        ];
    }
}
