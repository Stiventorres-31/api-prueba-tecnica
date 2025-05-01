<?php

namespace App\Repositories;

interface TransactionRepositoryInterface
{
    public function paginate(int $perPage = 5);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
}
