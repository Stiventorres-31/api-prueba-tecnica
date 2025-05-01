<?php

namespace App\Repositories;

use App\Models\PaymentMethod;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function all()
    {
        return PaymentMethod::all();
    }
}
