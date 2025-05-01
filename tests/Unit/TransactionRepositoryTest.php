<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionRepositoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_can_create_transaction()
    {
        $repo = new TransactionRepository();
        $data = Transaction::factory()->make()->toArray();

        $transaction = $repo->create($data);

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id]);
    }
}
