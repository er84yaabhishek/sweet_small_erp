<?php

namespace Tests\Unit\Services;

use App\Repositories\ProductionLogRepository;
use App\Repositories\RecipeRepository;
use App\Services\ProductionLogService;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class ProductionLogServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function makeService(&$logRepo = null, &$recipeRepo = null): ProductionLogService
    {
        $logRepo = Mockery::mock(ProductionLogRepository::class);
        $recipeRepo = Mockery::mock(RecipeRepository::class);

        return new ProductionLogService($logRepo, $recipeRepo);
    }

    public function test_get_all_logs_delegates(): void
    {
        $service = $this->makeService($logRepo);
        $logRepo->shouldReceive('getAll')->once()->with(20)->andReturn('list');

        $this->assertSame('list', $service->getAllLogs());
    }

    public function test_get_log_by_id_delegates(): void
    {
        $service = $this->makeService($logRepo);
        $logRepo->shouldReceive('findById')->once()->with(7)->andReturn('log');

        $this->assertSame('log', $service->getLogById(7));
    }

    public function test_create_log_derives_item_and_quantity_from_recipe(): void
    {
        Auth::shouldReceive('id')->andReturn(55);

        $service = $this->makeService($logRepo, $recipeRepo);

        $recipe = (object) ['item_id' => 12, 'batch_qty' => 10];
        $recipeRepo->shouldReceive('findById')->once()->with(3)->andReturn($recipe);

        $logRepo->shouldReceive('create')->once()->with(Mockery::on(function ($data) {
            return $data['recipe_id'] === 3
                && $data['item_id'] === 12
                && $data['qty_produced'] === 40
                && $data['batches'] === 4
                && $data['created_by'] === 55;
        }))->andReturn('created');

        $this->assertSame('created', $service->createLog(['recipe_id' => 3, 'batches' => 4]));
    }
}
