<?php

namespace App\Services;

use App\Repositories\Contracts\ReturnRepository;

abstract class ReturnService
{
    protected ReturnRepository $returnRepo;

    public function __construct(ReturnRepository $returnRepo)
    {
        $this->returnRepo = $returnRepo;
    }

    public function getAllReturns($perPage = 20)
    {
        return $this->returnRepo->getAll($perPage);
    }

    public function getReturnById($id)
    {
        return $this->returnRepo->findById($id);
    }

    public function createReturn(array $data, array $items)
    {
        $data['created_by'] = auth()->id();

        return $this->returnRepo->create($data, $items);
    }
}
