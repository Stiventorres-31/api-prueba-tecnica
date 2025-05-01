<?php

namespace App\Repositories;

interface PaymentMethodRepositoryInterface
{
    public function findByName(string $name);
    public function find(int $id);
}
