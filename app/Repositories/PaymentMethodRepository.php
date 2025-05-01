<?php

namespace App\Repositories;

use App\Models\PaymentMethod;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function findByName(string $name)
    {
        return PaymentMethod::where('name', $name)->firstOrFail();
    }

    public function find(int $id)
    {
        return PaymentMethod::findOrFail($id);
    }
}
