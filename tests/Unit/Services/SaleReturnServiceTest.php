<?php

namespace Tests\Unit\Services;

use App\Repositories\SaleReturnRepository;
use App\Services\SaleReturnService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class SaleReturnServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_returns_delegates(): void
    {
        $repo = Mockery::mock(SaleReturnRepository::class);
        $repo->shouldReceive('getAll')->once()->with(20)->andReturn('list');

        $this->assertSame('list', (new SaleReturnService($repo))->getAllReturns());
    }

    public function test_get_return_by_id_delegates(): void
    {
        $repo = Mockery::mock(SaleReturnRepository::class);
        $repo->shouldReceive('findById')->once()->with(6)->andReturn('return');

        $this->assertSame('return', (new SaleReturnService($repo))->getReturnById(6));
    }

    public function test_create_return_sets_creator_and_passes_items(): void
    {
        Auth::shouldReceive('id')->andReturn(21);

        $items = [['item_id' => 9, 'qty' => 1]];
        $repo = Mockery::mock(SaleReturnRepository::class);
        $repo->shouldReceive('create')->once()->with(
            Mockery::on(fn ($data) => $data['created_by'] === 21 && $data['sale_id'] === 7),
            $items
        )->andReturn('created');

        $this->assertSame('created', (new SaleReturnService($repo))->createReturn(['sale_id' => 7], $items));
    }
}
