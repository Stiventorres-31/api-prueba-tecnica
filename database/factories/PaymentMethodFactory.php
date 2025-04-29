<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $now = Carbon::now();

        $methods = [
            [
                'name' => 'cash',
                'config' => ['fee' => rand(1, 5)],
            ],
            [
                'name' => 'online',
                'config' => ['fee' => rand(1, 5)],
            ],
            [
                'name' => 'crypto',
                'config' => ['fee' => rand(1, 5)],
            ],
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['name' => $method['name']],
                [
                    'config' => json_encode($method['config']),
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
