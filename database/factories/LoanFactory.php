<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomFloat(2, 0, 50000),
            'interest_rate' => fake()->randomFloat(2, 0, 100),
            'duration' => fake()->numberBetween(1, 10),
            'lender_id' => User::factory(),
            'borrower_id' => User::factory(),
        ];
    }
}
