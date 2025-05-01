<?php

namespace App\Services;

use App\Repositories\PaymentMethodRepositoryInterface;

class PaymentMethodService
{
    protected $paymentRepo;

    public function __construct(PaymentMethodRepositoryInterface $paymentRepo)
    {
        $this->paymentRepo = $paymentRepo;
    }

    public function getByName(string $name)
    {
        return $this->paymentRepo->findByName($name);
    }

    public function getById(int $id)
    {
        return $this->paymentRepo->find($id);
    }
}
