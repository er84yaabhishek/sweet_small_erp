<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository
{
    public function getAll($perPage = 20, $search = null)
    {
        $query = Supplier::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }
        return $query->orderBy('name')->paginate($perPage);
    }

    public function findById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function update($id, array $data)
    {
        $supplier = $this->findById($id);
        $supplier->update($data);
        return $supplier;
    }

    public function delete($id)
    {
        $supplier = $this->findById($id);
        return $supplier->delete();
    }

    public function getAllSuppliers()
    {
        return Supplier::orderBy('name')->get();
    }
}