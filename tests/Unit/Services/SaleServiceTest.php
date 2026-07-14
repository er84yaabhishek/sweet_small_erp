<?php

namespace Tests\Unit\Services;

use App\Repositories\CustomerRepository;
use App\Repositories\ItemRepository;
use App\Repositories\SaleRepository;
use App\Services\SaleService;
use Mockery;
use Tests\TestCase;

class SaleServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function makeService(&$saleRepo = null, &$customerRepo = null, &$itemRepo = null): SaleService
    {
        $saleRepo = Mockery::mock(SaleRepository::class);
        $customerRepo = Mockery::mock(CustomerRepository::class);
        $itemRepo = Mockery::mock(ItemRepository::class);

        return new SaleService($saleRepo, $customerRepo, $itemRepo);
    }

    public function test_create_sale_computes_total_with_discount_and_round_off(): void
    {
        $items = [
            ['line_total' => 200.0, 'tax_amount' => 20.0],
            ['line_total' => 100.0, 'tax_amount' => 10.0],
        ];
        $payments = [['amount' => 300.0, 'method' => 'cash']];

        $service = $this->makeService($saleRepo);

        $saleRepo->shouldReceive('create')->once()->with(
            Mockery::on(function ($data) {
                // subtotal 300, tax 30, discount 25, round_off 0.5 => 305.5
                return $data['subtotal'] === 300.0
                    && $data['tax_amount'] === 30.0
                    && $data['total_amount'] === 305.5
                    && $data['status'] === 'completed'
                    && $data['sale_date'] === '2024-01-02'
                    && $data['sale_time'] === '10:30:00';
            }),
            $items,
            $payments
        )->andReturn('sale');

        $data = [
            'discount_amount' => 25.0,
            'round_off' => 0.5,
            'sale_date' => '2024-01-02',
            'sale_time' => '10:30:00',
        ];

        $this->assertSame('sale', $service->createSale($data, $items, $payments));
    }

    public function test_create_sale_defaults_discount_and_round_off_to_zero(): void
    {
        $items = [['line_total' => 50.0, 'tax_amount' => 5.0]];

        $service = $this->makeService($saleRepo);

        $saleRepo->shouldReceive('create')->once()->with(
            Mockery::on(function ($data) {
                return $data['subtotal'] === 50.0
                    && $data['tax_amount'] === 5.0
                    && $data['total_amount'] === 55.0
                    && $data['status'] === 'completed'
                    && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['sale_date']) === 1
                    && preg_match('/^\d{2}:\d{2}:\d{2}$/', $data['sale_time']) === 1;
            }),
            $items,
            []
        )->andReturn('sale');

        $this->assertSame('sale', $service->createSale([], $items, []));
    }

    public function test_get_all_sales_delegates_with_filters(): void
    {
        $service = $this->makeService($saleRepo);
        $filters = ['from' => '2024-01-01'];
        $saleRepo->shouldReceive('getAll')->once()->with(25, $filters)->andReturn('list');

        $this->assertSame('list', $service->getAllSales(25, $filters));
    }

    public function test_get_sale_by_id_delegates(): void
    {
        $service = $this->makeService($saleRepo);
        $saleRepo->shouldReceive('findById')->once()->with(11)->andReturn('sale');

        $this->assertSame('sale', $service->getSaleById(11));
    }

    public function test_get_sellable_items_returns_paginator_items(): void
    {
        $service = $this->makeService($saleRepo, $customerRepo, $itemRepo);

        $paginator = Mockery::mock();
        $paginator->shouldReceive('items')->once()->andReturn(['x']);
        $itemRepo->shouldReceive('getAll')->once()->with(1000, ['is_sellable' => true])->andReturn($paginator);

        $this->assertSame(['x'], $service->getSellableItems());
    }

    public function test_get_customers_delegates_to_customer_repository(): void
    {
        $service = $this->makeService($saleRepo, $customerRepo);
        $customerRepo->shouldReceive('getAllCustomers')->once()->andReturn('customers');

        $this->assertSame('customers', $service->getCustomers());
    }
}
