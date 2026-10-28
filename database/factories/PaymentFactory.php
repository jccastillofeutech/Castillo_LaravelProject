<?php

namespace Database\Factories;

use App\Models\MembershipTransaction;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'membership_transaction_id' => MembershipTransaction::factory(),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'method' => 'cash',
            'reference' => null,
            'paid_at' => now(),
        ];
    }
}
