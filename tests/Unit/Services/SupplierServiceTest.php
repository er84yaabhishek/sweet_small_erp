<?php

namespace Tests\Unit\Services;

use App\Repositories\SupplierRepository;
use App\Services\SupplierService;
use Mockery;
use Tests\TestCase;

class SupplierServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_suppliers_passes_per_page_and_search(): void
    {
        $repo = Mockery::mock(SupplierRepository::class);
        $repo->shouldReceive('getAll')->once()->with(30, 'acme')->andReturn('list');

        $this->assertSame('list', (new SupplierService($repo))->getAllSuppliers(30, 'acme'));
    }

    public function test_get_all_suppliers_defaults(): void
    {
        $repo = Mockery::mock(SupplierRepository::class);
        $repo->shouldReceive('getAll')->once()->with(20, null)->andReturn('default');

        $this->assertSame('default', (new SupplierService($repo))->getAllSuppliers());
    }

    public function test_get_supplier_by_id_delegates(): void
    {
        $repo = Mockery::mock(SupplierRepository::class);
        $repo->shouldReceive('findById')->once()->with(4)->andReturn('supplier');

        $this->assertSame('supplier', (new SupplierService($repo))->getSupplierById(4));
    }

    public function test_create_supplier_delegates(): void
    {
        $data = ['name' => 'Acme'];
        $repo = Mockery::mock(SupplierRepository::class);
        $repo->shouldReceive('create')->once()->with($data)->andReturn('created');

        $this->assertSame('created', (new SupplierService($repo))->createSupplier($data));
    }

    public function test_update_supplier_delegates(): void
    {
        $data = ['name' => 'Acme 2'];
        $repo = Mockery::mock(SupplierRepository::class);
        $repo->shouldReceive('update')->once()->with(4, $data)->andReturn('updated');

        $this->assertSame('updated', (new SupplierService($repo))->updateSupplier(4, $data));
    }

    public function test_delete_supplier_delegates(): void
    {
        $repo = Mockery::mock(SupplierRepository::class);
        $repo->shouldReceive('delete')->once()->with(4)->andReturn(true);

        $this->assertTrue((new SupplierService($repo))->deleteSupplier(4));
    }

    public function test_get_list_delegates_to_get_all_suppliers(): void
    {
        $repo = Mockery::mock(SupplierRepository::class);
        $repo->shouldReceive('getAllSuppliers')->once()->andReturn('all');

        $this->assertSame('all', (new SupplierService($repo))->getList());
    }
}
