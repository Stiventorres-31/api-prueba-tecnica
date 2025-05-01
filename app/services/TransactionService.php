<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function listPaginated(int $page = 5)
    {
        return $this->transactionRepository->paginate($page);
    }

    public function getById(int $id)
    {
        return Transaction::with(['customer', 'paymentMethod'])->findOrFail($id);
    }

    public function createTransaction(array $validated)
    {
        return DB::transaction(function () use ($validated) {
            $customer = Customer::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'type_document' => $validated['type_document'],
                'number_document' => $validated['number_document'],
                'preferences' => ['raw_data' => $validated]
            ]);

            return $this->transactionRepository->create([
                'customer_id' => $customer->id,
                'payment_method_id' => null,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'status' => 'pending',
                'metadata' => ['raw_data' => $validated]
            ]);
        });
    }
    public function generatePayment(array $validated)
    {
        return DB::transaction(function () use ($validated) {
            $paymentMethod = PaymentMethod::where('name', $validated['payment_method'])->firstOrFail();
            $fee = $paymentMethod->config['fee'] ?? 0;
            $total = $validated['amount'] + $fee;

            $transaction = $this->transactionRepository->update($validated['transaction_id'], [
                'customer_id' => $validated['customer_id'],
                'payment_method_id' => $paymentMethod->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'fee' => $fee,
                'total' => $total,
                'status' => 'completed',
                'metadata' => ['raw_data' => $validated],
            ]);

            return [
                'transaction_id' => $transaction->id,
                'url_payment' => 'http://localhost:5173/transaction/' . $transaction->id,
            ];
        });
    }
}
