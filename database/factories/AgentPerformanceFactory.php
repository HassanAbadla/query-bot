<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AgentPerformance;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AgentPerformance>
 */
class AgentPerformanceFactory extends Factory
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
            'calls_handled' => $this->faker->numberBetween(1, 100),
            'average_call_duration' => $this->faker->numberBetween(1, 300),
            'deals' => $this->faker->numberBetween(0, 50),
            'booked' => $this->faker->numberBetween(0, 50),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
