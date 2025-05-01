<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index_returns_paginated_transactions()
    {
        Transaction::factory()->count(10)->create();

        $response = $this->getJson('/api/transactions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'code',
                'message',
                'result' => [
                    'transactions' => [
                        'data',
                        'current_page',
                        'last_page'
                    ]
                ]
            ]);
    }

    public function test_can_create_transaction_with_random_data()
    {
        $payload = [
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'type_document' => 'CC',
            'number_document' => fake()->numerify('##########'),
            'amount' => fake()->randomFloat(2, 1000, 100000),
            'currency' => 'COP',
        ];

        $response = $this->postJson('/api/transactions', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'code',
                'message',
                'result' => [
                    'transaction_id',
                    'url_payment',
                ]
            ]);
    }
    public function test_can_update_transaction()
    {
        // Crear entidades relacionadas
        $customer = Customer::factory()->create();
        $paymentMethod = PaymentMethod::where('name', 'online')->firstOrFail();


        // Preparar datos de transacción
        $payload = [
            'name' => $customer->name,
            'email' => $customer->email,
            'type_document' => $customer->type_document,
            'number_document' => $customer->number_document,
            'amount' => 20000,
            'currency' => 'COP',
            'payment_method' => $paymentMethod->name,
        ];

        // Crear transacción
        $createResponse = $this->postJson('/api/transactions', $payload);
        $createResponse->assertStatus(201);

        // Obtener el ID generado
        $transactionId = $createResponse->json('result.transaction_id');

        // Simular confirmación del pago (actualización)
        $updatePayload = array_merge($payload, [
            'transaction_id' => $transactionId,
            'customer_id' => $customer->id,
            'payment_method_id' => $paymentMethod->id,
            'currency' =>$payload['currency']
        ]);

        $updateResponse = $this->putJson("/api/transactions/{$transactionId}", $updatePayload);

        $updateResponse->assertStatus(200)
            ->assertJsonFragment([
                'transaction_id' => $transactionId,
            ]);

        // Validar en base de datos
        $this->assertDatabaseHas('transactions', [
            'id' => $transactionId,
            'customer_id' => $customer->id,
            'payment_method_id' => $paymentMethod->id,
            'status' => 'completed',
            'fee' => $paymentMethod->config['fee']
        ]);
    }
}
