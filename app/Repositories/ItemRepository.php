<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository extends BaseRepository
{
    protected function modelClass(): string
    {
        return Item::class;
    }

    public function getAll($perPage = 20, $filters = [])
    {
        $query = Item::with(['category', 'unit', 'taxRate']);
        if (!empty($filters['item_type'])) {
            $query->where('item_type', $filters['item_type']);
        }
        if (!empty($filters['is_sellable'])) {
            $query->where('is_sellable', $filters['is_sellable']);
        }
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%'.$filters['search'].'%')
                  ->orWhere('sku', 'like', '%'.$filters['search'].'%')
                  ->orWhere('barcode', 'like', '%'.$filters['search'].'%');
            });
        }
        return $query->orderBy('name')->paginate($perPage);
    }

    public function findById($id)
    {
        return Item::with(['category', 'unit', 'taxRate'])->findOrFail($id);
    }

    public function getSellableItems()
    {
        return Item::where('is_sellable', true)->where('is_active', true)->orderBy('name')->get();
    }
}