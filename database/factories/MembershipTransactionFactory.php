<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\MembershipLevel;
use App\Models\MembershipTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MembershipTransaction>
 */
class MembershipTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'membership_level_id' => MembershipLevel::factory(),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'starts_at' => now()->toDateString(),
            'expires_at' => now()->addYear()->toDateString(),
        ];
    }
}
