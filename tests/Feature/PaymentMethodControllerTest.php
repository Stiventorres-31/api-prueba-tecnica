<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentMethodControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/payment-methods');

        $response->assertStatus(200)->assertJsonStructure([
            'success',
            'code',
            'message',
            'result' => ['payment_methods']
        ]);
    }
}
