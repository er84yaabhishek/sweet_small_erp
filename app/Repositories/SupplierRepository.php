<?php

namespace App\Repositories;

use App\Models\Supplier;

class SupplierRepository extends BaseRepository
{
    protected function modelClass(): string
    {
        return Supplier::class;
    }

    public function getAll($perPage = 20, $search = null)
    {
        $query = Supplier::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }
        return $query->orderBy('name')->paginate($perPage);
    }

    public function getAllSuppliers()
    {
        return Supplier::orderBy('name')->get();
    }
}