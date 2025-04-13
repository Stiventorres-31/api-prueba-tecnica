<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
    $table->unsignedBigInteger('customer_id');
    $table->unsignedBigInteger('payment_method_id')->nullable();
    $table->decimal('amount', 10, 2);
    $table->string('currency');
    $table->decimal('fee', 10, 2)->nullable();
    $table->decimal('total', 10, 2)->nullable();
    $table->string('status'); 
    $table->json('metadata'); 
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
