<?php

namespace Tests\Unit\Services;

use App\Repositories\ItemRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SupplierRepository;
use App\Services\PurchaseService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class PurchaseServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function makeService(&$purchaseRepo = null, &$supplierRepo = null, &$itemRepo = null): PurchaseService
    {
        $purchaseRepo = Mockery::mock(PurchaseRepository::class);
        $supplierRepo = Mockery::mock(SupplierRepository::class);
        $itemRepo = Mockery::mock(ItemRepository::class);

        return new PurchaseService($purchaseRepo, $supplierRepo, $itemRepo);
    }

    public function test_create_purchase_computes_totals_and_sets_creator(): void
    {
        Auth::shouldReceive('id')->andReturn(42);

        $items = [
            ['line_total' => 100.0, 'tax_amount' => 18.0],
            ['line_total' => 50.0, 'tax_amount' => 9.0],
        ];

        $service = $this->makeService($purchaseRepo, $supplierRepo, $itemRepo);

        $purchaseRepo->shouldReceive('create')->once()->with(
            Mockery::on(function ($data) {
                return $data['subtotal'] === 150.0
                    && $data['tax_amount'] === 27.0
                    && $data['total_amount'] === 177.0
                    && $data['created_by'] === 42
                    && $data['supplier_id'] === 3;
            }),
            $items
        )->andReturn('purchase');

        $result = $service->createPurchase(['supplier_id' => 3], $items);

        $this->assertSame('purchase', $result);
    }

    public function test_create_purchase_handles_empty_items(): void
    {
        Auth::shouldReceive('id')->andReturn(1);

        $service = $this->makeService($purchaseRepo);

        $purchaseRepo->shouldReceive('create')->once()->with(
            Mockery::on(function ($data) {
                return $data['subtotal'] === 0
                    && $data['tax_amount'] === 0
                    && $data['total_amount'] === 0;
            }),
            []
        )->andReturn('empty');

        $this->assertSame('empty', $service->createPurchase([], []));
    }

    public function test_get_all_purchases_delegates_with_filters(): void
    {
        $service = $this->makeService($purchaseRepo);
        $filters = ['status' => 'pending'];
        $purchaseRepo->shouldReceive('getAll')->once()->with(10, $filters)->andReturn('list');

        $this->assertSame('list', $service->getAllPurchases(10, $filters));
    }

    public function test_get_purchase_by_id_delegates(): void
    {
        $service = $this->makeService($purchaseRepo);
        $purchaseRepo->shouldReceive('findById')->once()->with(5)->andReturn('purchase');

        $this->assertSame('purchase', $service->getPurchaseById(5));
    }

    public function test_update_purchase_delegates(): void
    {
        $service = $this->makeService($purchaseRepo);
        $data = ['status' => 'received'];
        $purchaseRepo->shouldReceive('update')->once()->with(6, $data)->andReturn('updated');

        $this->assertSame('updated', $service->updatePurchase(6, $data));
    }

    public function test_delete_purchase_delegates(): void
    {
        $service = $this->makeService($purchaseRepo);
        $purchaseRepo->shouldReceive('delete')->once()->with(6)->andReturn(true);

        $this->assertTrue($service->deletePurchase(6));
    }

    public function test_get_suppliers_delegates_to_supplier_repository(): void
    {
        $service = $this->makeService($purchaseRepo, $supplierRepo);
        $supplierRepo->shouldReceive('getAllSuppliers')->once()->andReturn('suppliers');

        $this->assertSame('suppliers', $service->getSuppliers());
    }

    public function test_get_purchasable_items_returns_paginator_items(): void
    {
        $service = $this->makeService($purchaseRepo, $supplierRepo, $itemRepo);

        $paginator = Mockery::mock();
        $paginator->shouldReceive('items')->once()->andReturn(['a', 'b']);
        $itemRepo->shouldReceive('getAll')->once()->with(1000, ['is_purchasable' => true])->andReturn($paginator);

        $this->assertSame(['a', 'b'], $service->getPurchasableItems());
    }
}
