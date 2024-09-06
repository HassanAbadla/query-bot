<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CallLog;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CallLog>
 */
class CallLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'agent_id' => $this->faker->randomNumber(),
            'call_status' => $this->faker->randomElement(['answered', 'missed']),
            'call_duration' => $this->faker->numberBetween(1, 300),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
