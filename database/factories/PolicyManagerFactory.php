<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PolicyManager>
 */
class PolicyManagerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'policy_number' => $this->faker->unique()->numerify('POLICY###'),
            'policy_type_id' => $this->faker->numberBetween(1, 5),
            'status' => $this->faker->randomElement(['Active', 'Expired']),
            'premium_amount' => $this->faker->randomFloat(2, 100, 10000),
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'next_of_kin' => $this->faker->name,
        ];
    }
}
