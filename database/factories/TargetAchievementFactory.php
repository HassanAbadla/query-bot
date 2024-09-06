<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TargetAchievement;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TargetAchievement>
 */
class TargetAchievementFactory extends Factory
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
            'target' => $this->faker->numberBetween(1, 100),
            'achievement' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
