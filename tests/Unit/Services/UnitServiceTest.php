<?php

namespace Tests\Unit\Services;

use App\Repositories\UnitRepository;
use App\Services\UnitService;
use Mockery;
use Tests\TestCase;

class UnitServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_units_delegates_to_repository_with_per_page(): void
    {
        $repo = Mockery::mock(UnitRepository::class);
        $repo->shouldReceive('getAll')->once()->with(15)->andReturn('paginated');

        $service = new UnitService($repo);

        $this->assertSame('paginated', $service->getAllUnits(15));
    }

    public function test_get_all_units_uses_default_per_page(): void
    {
        $repo = Mockery::mock(UnitRepository::class);
        $repo->shouldReceive('getAll')->once()->with(20)->andReturn('default');

        $service = new UnitService($repo);

        $this->assertSame('default', $service->getAllUnits());
    }

    public function test_get_unit_by_id_delegates_to_repository(): void
    {
        $repo = Mockery::mock(UnitRepository::class);
        $repo->shouldReceive('findById')->once()->with(7)->andReturn('unit');

        $service = new UnitService($repo);

        $this->assertSame('unit', $service->getUnitById(7));
    }

    public function test_create_unit_delegates_to_repository(): void
    {
        $data = ['name' => 'Kilogram', 'short_name' => 'kg'];
        $repo = Mockery::mock(UnitRepository::class);
        $repo->shouldReceive('create')->once()->with($data)->andReturn('created');

        $service = new UnitService($repo);

        $this->assertSame('created', $service->createUnit($data));
    }

    public function test_update_unit_delegates_to_repository(): void
    {
        $data = ['name' => 'Gram', 'short_name' => 'g'];
        $repo = Mockery::mock(UnitRepository::class);
        $repo->shouldReceive('update')->once()->with(3, $data)->andReturn('updated');

        $service = new UnitService($repo);

        $this->assertSame('updated', $service->updateUnit(3, $data));
    }

    public function test_delete_unit_delegates_to_repository(): void
    {
        $repo = Mockery::mock(UnitRepository::class);
        $repo->shouldReceive('delete')->once()->with(9)->andReturn(true);

        $service = new UnitService($repo);

        $this->assertTrue($service->deleteUnit(9));
    }
}
