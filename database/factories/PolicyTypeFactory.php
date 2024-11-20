<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PolicyType>
 */
class PolicyTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Life Insurance', 'Health Insurance', 'Auto Insurance', 'Home Insurance', 'Travel Insurance']),  
            'price' => $this->faker->randomFloat(2, 10, 1000), 
            'description' => $this->faker->sentence,
            'user_id' => \App\Models\User::factory(), 
        ];
    }
}
