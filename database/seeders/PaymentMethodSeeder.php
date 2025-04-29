<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Database\Factories\PaymentMethodFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::factory(3)->create();
    }
}
