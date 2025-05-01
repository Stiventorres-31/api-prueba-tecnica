<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'payment_method_id' => null,
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'currency' => 'COP',
            'fee' => 0,
            'total' => 0,
            'status' => 'pending',
            'metadata' => ['raw_data' => []],
        ];
    }
}
