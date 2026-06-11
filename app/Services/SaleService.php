<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ItemRepository;

class SaleService
{
    protected $saleRepo;
    protected $customerRepo;
    protected $itemRepo;

    public function __construct(SaleRepository $saleRepo, CustomerRepository $customerRepo, ItemRepository $itemRepo)
    {
        $this->saleRepo = $saleRepo;
        $this->customerRepo = $customerRepo;
        $this->itemRepo = $itemRepo;
    }

    public function getAllSales($perPage = 20, $filters = [])
    {
        return $this->saleRepo->getAll($perPage, $filters);
    }

    public function getSaleById($id)
    {
        return $this->saleRepo->findById($id);
    }

    public function createSale(array $data, array $items, array $payments)
    {
        $subtotal = collect($items)->sum('line_total');
        $discount = $data['discount_amount'] ?? 0;
        $tax = collect($items)->sum('tax_amount');
        $roundOff = $data['round_off'] ?? 0;
        $total = $subtotal + $tax - $discount + $roundOff;

        $data['subtotal'] = $subtotal;
        $data['tax_amount'] = $tax;
        $data['total_amount'] = $total;
        $data['status'] = 'completed';
        $data['sale_date'] = $data['sale_date'] ?? date('Y-m-d');
        $data['sale_time'] = $data['sale_time'] ?? date('H:i:s');

        return $this->saleRepo->create($data, $items, $payments);
    }

    public function getSellableItems()
    {
        $items = $this->itemRepo->getAll(1000, ['is_sellable' => true]);
        return $items->items();
    }

    public function getCustomers()
    {
        return $this->customerRepo->getAllCustomers();
    }
}
