<?php

namespace App\Services;

use App\Models\Item;
use App\Repositories\ItemRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemService
{
    protected $itemRepo;

    public function __construct(ItemRepository $itemRepo)
    {
        $this->itemRepo = $itemRepo;
    }

    public function getAllItems($perPage = 20, $filters = [])
    {
        return $this->itemRepo->getAll($perPage, $filters);
    }

    public function getItemById($id)
    {
        return $this->itemRepo->findById($id);
    }

    public function createItem(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['image']) && $data['image']->isValid()) {
                $path = $data['image']->store('items', 'public');
                $data['image'] = $path;
            }

            if (empty($data['sku'])) {
                $data['sku'] = $this->generateSku($data['name']);
            }

            return $this->itemRepo->create($data);
        });
    }

    public function updateItem($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $item = $this->itemRepo->findById($id);
            if (isset($data['image']) && $data['image']->isValid()) {
                if ($item->image && Storage::disk('public')->exists($item->image)) {
                    Storage::disk('public')->delete($item->image);
                }
                $path = $data['image']->store('items', 'public');
                $data['image'] = $path;
            }

            return $this->itemRepo->update($id, $data);
        });
    }

    public function deleteItem($id)
    {
        return DB::transaction(function () use ($id) {
            $item = $this->itemRepo->findById($id);
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }

            return $this->itemRepo->delete($id);
        });
    }

    private function generateSku($name)
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $count = Item::where('sku', 'like', $prefix.'%')->count() + 1;
        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}