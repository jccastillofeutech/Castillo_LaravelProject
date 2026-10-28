<?php

namespace Database\Factories;

use App\Models\MembershipLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MembershipLevel>
 */
class MembershipLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'fee' => fake()->randomFloat(2, 0, 5000),
            'validity_days' => 365,
            'benefits' => fake()->sentence(),
        ];
    }
}
