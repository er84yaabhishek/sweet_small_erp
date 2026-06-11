<?php

namespace App\Services;

use App\Repositories\PurchaseRepository;
use App\Repositories\SupplierRepository;
use App\Repositories\ItemRepository;

class PurchaseService
{
    protected $purchaseRepo;
    protected $supplierRepo;
    protected $itemRepo;

    public function __construct(PurchaseRepository $purchaseRepo, SupplierRepository $supplierRepo, ItemRepository $itemRepo)
    {
        $this->purchaseRepo = $purchaseRepo;
        $this->supplierRepo = $supplierRepo;
        $this->itemRepo = $itemRepo;
    }

    public function getAllPurchases($perPage = 20, $filters = [])
    {
        return $this->purchaseRepo->getAll($perPage, $filters);
    }

    public function getPurchaseById($id)
    {
        return $this->purchaseRepo->findById($id);
    }

    public function createPurchase(array $data, array $items)
    {
        // Calculate totals
        $subtotal = collect($items)->sum('line_total');
        $taxAmount = collect($items)->sum('tax_amount');
        $total = $subtotal + $taxAmount;
        $data['subtotal'] = $subtotal;
        $data['tax_amount'] = $taxAmount;
        $data['total_amount'] = $total;
        $data['created_by'] = auth()->id();

        return $this->purchaseRepo->create($data, $items);
    }

    public function updatePurchase($id, array $data)
    {
        return $this->purchaseRepo->update($id, $data);
    }

    public function deletePurchase($id)
    {
        return $this->purchaseRepo->delete($id);
    }

    public function getSuppliers()
    {
        return $this->supplierRepo->getAllSuppliers();
    }

    public function getPurchasableItems()
    {
        return $this->itemRepo->getAll(1000, ['is_purchasable' => true])->items(); // Hack: get all items where purchasable
        // Better to create a method in ItemRepository: getPurchasableItems()
    }
}