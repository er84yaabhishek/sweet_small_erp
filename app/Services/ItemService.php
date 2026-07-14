<?php

namespace App\Services;

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
        DB::beginTransaction();
        try {
            // Handle image upload
            if (isset($data['image']) && $data['image']->isValid()) {
                $path = $data['image']->store('items', 'public');
                $data['image'] = $path;
            }
            // Auto-generate SKU if not provided
            if (empty($data['sku'])) {
                $data['sku'] = $this->generateSku($data['name']);
            }
            $item = $this->itemRepo->create($data);
            DB::commit();
            return $item;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateItem($id, array $data)
    {
        DB::beginTransaction();
        try {
            $item = $this->itemRepo->findById($id);
            if (isset($data['image']) && $data['image']->isValid()) {
                // Delete old image
                if ($item->image && Storage::disk('public')->exists($item->image)) {
                    Storage::disk('public')->delete($item->image);
                }
                $path = $data['image']->store('items', 'public');
                $data['image'] = $path;
            }
            $item = $this->itemRepo->update($id, $data);
            DB::commit();
            return $item;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteItem($id)
    {
        DB::beginTransaction();
        try {
            $item = $this->itemRepo->findById($id);
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $result = $this->itemRepo->delete($id);
            DB::commit();
            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateSku($name)
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 3));
        $count = Item::where('sku', 'like', $prefix.'%')->count() + 1;
        return $prefix . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}