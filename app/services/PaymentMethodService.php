<?php

namespace App\Services;

use App\Repositories\PaymentMethodRepositoryInterface;

class PaymentMethodService
{
    protected $paymentRepository;

    public function __construct(PaymentMethodRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function listAll()
    {
        return $this->paymentRepository->all();
    }
}
