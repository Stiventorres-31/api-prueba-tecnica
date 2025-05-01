<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface {
    public function paginate(int $page=5){
        return Transaction::with(['customer','paymentMethod'])->paginate($page);
    }

    public function findById(int $id){
        return Transaction::with('customer')->firstOrFail($id);
    }

    public function create(array $data){
        return Transaction::create($data);
    }
    public function update(int $id, array $data){
        $transaction=Transaction::findOrFail($id);
        $transaction->update($data);
        return $transaction;
    }
}
