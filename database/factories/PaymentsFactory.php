<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'policy_id' => \App\Models\PolicyManager::factory(),
            'reference' => $this->faker->unique()->uuid,
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'status' => $this->faker->randomElement(['success', 'failed']),
            'user_id' => \App\Models\User::factory(),
            'channel' => $this->faker->randomElement(['card', 'mobile_money']),
            'currency' => 'GHS',
            'mobile_money_number' => $this->faker->phoneNumber,
            'payer_ip_address' => $this->faker->ipv4,
            'paid_at' => $this->faker->dateTime,
        ];
    }
}
