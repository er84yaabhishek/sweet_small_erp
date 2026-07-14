<?php

namespace App\Repositories\Contracts;

interface ReturnRepository
{
    public function getAll($perPage = 20);

    public function findById($id);

    public function create(array $data, array $items);
}
