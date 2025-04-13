<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // 'cash', 'online', 'crypto'
            $table->json('config'); 
            $table->timestamps();
        });

        // Insertar los métodos de pago predefinidos
        $paymentMethods = [
            [
                'name' => 'cash',
                'config' => json_encode(['fee' => rand(100, 1000)]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'online',
                'config' => json_encode(['fee' => rand(100, 1000)]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'crypto',
                'config' => json_encode(['fee' => rand(100, 1000)]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('payment_methods')->insert($paymentMethods);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
