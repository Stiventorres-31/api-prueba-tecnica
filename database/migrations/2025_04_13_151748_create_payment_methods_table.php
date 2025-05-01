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
        //CREE UN SEED PARA LOS DATOS PREDEFINIDO AQUI Y EL NAME LO COLOQUE COMO UNICO PARA EVITAR DUPLICIDAD
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // 'cash', 'online', 'crypto'
            $table->json('config');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }

    public function seedDefaultPaymentMethod(){
        $now = now();
        $dataDefault= collect(['cash','online','crypto'])->map(function($namePaymentMethod)use ($now){
            return [
                'name' => $namePaymentMethod,
                'config' => json_encode(['fee' => rand(100, 1000)]),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        });
        DB::table('payment_methods')->insert($dataDefault->toArray());
    }
};
