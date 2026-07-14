<?php

namespace Tests\Unit\Services;

use App\Repositories\PurchaseReturnRepository;
use App\Services\PurchaseReturnService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class PurchaseReturnServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_returns_delegates(): void
    {
        $repo = Mockery::mock(PurchaseReturnRepository::class);
        $repo->shouldReceive('getAll')->once()->with(20)->andReturn('list');

        $this->assertSame('list', (new PurchaseReturnService($repo))->getAllReturns());
    }

    public function test_get_return_by_id_delegates(): void
    {
        $repo = Mockery::mock(PurchaseReturnRepository::class);
        $repo->shouldReceive('findById')->once()->with(2)->andReturn('return');

        $this->assertSame('return', (new PurchaseReturnService($repo))->getReturnById(2));
    }

    public function test_create_return_sets_creator_and_passes_items(): void
    {
        Auth::shouldReceive('id')->andReturn(8);

        $items = [['item_id' => 1, 'qty' => 2]];
        $repo = Mockery::mock(PurchaseReturnRepository::class);
        $repo->shouldReceive('create')->once()->with(
            Mockery::on(fn ($data) => $data['created_by'] === 8 && $data['purchase_id'] === 4),
            $items
        )->andReturn('created');

        $this->assertSame('created', (new PurchaseReturnService($repo))->createReturn(['purchase_id' => 4], $items));
    }
}
