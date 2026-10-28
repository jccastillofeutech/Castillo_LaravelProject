<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'membership_category_id' => MembershipCategory::factory(),
            'membership_level_id' => MembershipLevel::factory(),
            'joined_at' => now()->toDateString(),
            'is_active' => true,
            'notes' => null,
        ];
    }
}
