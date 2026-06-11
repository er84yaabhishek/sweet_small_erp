<?php
namespace App\Repositories;

use App\Models\Unit;

class UnitRepository
{
    public function getAll($perPage = 20)
    {
        return Unit::orderBy('name')->paginate($perPage);
    }

    public function findById($id)
    {
        return Unit::findOrFail($id);
    }

    public function create(array $data)
    {
        return Unit::create($data);
    }

    public function update($id, array $data)
    {
        $unit = $this->findById($id);
        $unit->update($data);
        return $unit;
    }

    public function delete($id)
    {
        $unit = $this->findById($id);
        return $unit->delete();
    }
}