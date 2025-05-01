<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Database\Factories\PaymentMethodFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'name'       => 'cash',
                'config'     => json_encode([
                    // Ejemplo: en efectivo no hay comisiones
                    'fee_percent' => 0,
                    'currencies'  => ['COP', 'USD'],
                ])
            ],
            [
                'name'       => 'online',
                'config'     => json_encode([
                    // Ejemplo: provider Stripe
                    'provider'   => 'stripe',
                    'api_key'    => env('STRIPE_API_KEY'),
                    'fee_percent'=> 2.9,
                ])
            ],
            [
                'name'       => 'crypto',
                'config'     => json_encode([
                    // Ejemplo: criptomonedas soportadas
                    'supported_coins' => ['BTC', 'ETH', 'USDT'],
                    'network_fee'     => 0.5,
                ])
            ],
        ]);
    }
}
