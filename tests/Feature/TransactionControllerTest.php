<?php

namespace Tests\Feature;

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
}
