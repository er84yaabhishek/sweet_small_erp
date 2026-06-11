<?php

namespace App\Services;

use App\Repositories\UnitRepository;

class UnitService
{
    protected $unitRepo;

    public function __construct(UnitRepository $unitRepo)
    {
        $this->unitRepo = $unitRepo;
    }

    public function getAllUnits($perPage = 20)
    {
        return $this->unitRepo->getAll($perPage);
    }

    public function getUnitById($id)
    {
        return $this->unitRepo->findById($id);
    }

    public function createUnit(array $data)
    {
        return $this->unitRepo->create($data);
    }

    public function updateUnit($id, array $data)
    {
        return $this->unitRepo->update($id, $data);
    }

    public function deleteUnit($id)
    {
        return $this->unitRepo->delete($id);
    }
}
