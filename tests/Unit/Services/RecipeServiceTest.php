<?php

namespace Tests\Unit\Services;

use App\Repositories\ItemRepository;
use App\Repositories\RecipeRepository;
use App\Repositories\UnitRepository;
use App\Services\RecipeService;
use Mockery;
use Tests\TestCase;

class RecipeServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function makeService(&$recipeRepo = null, &$itemRepo = null, &$unitRepo = null): RecipeService
    {
        $recipeRepo = Mockery::mock(RecipeRepository::class);
        $itemRepo = Mockery::mock(ItemRepository::class);
        $unitRepo = Mockery::mock(UnitRepository::class);

        return new RecipeService($recipeRepo, $itemRepo, $unitRepo);
    }

    public function test_get_all_recipes_delegates(): void
    {
        $service = $this->makeService($recipeRepo);
        $recipeRepo->shouldReceive('getAll')->once()->with(20)->andReturn('list');

        $this->assertSame('list', $service->getAllRecipes());
    }

    public function test_get_recipe_by_id_delegates(): void
    {
        $service = $this->makeService($recipeRepo);
        $recipeRepo->shouldReceive('findById')->once()->with(1)->andReturn('recipe');

        $this->assertSame('recipe', $service->getRecipeById(1));
    }

    public function test_create_recipe_passes_data_and_ingredients(): void
    {
        $service = $this->makeService($recipeRepo);
        $data = ['name' => 'Ladoo'];
        $ingredients = [['item_id' => 1, 'qty' => 2]];
        $recipeRepo->shouldReceive('create')->once()->with($data, $ingredients)->andReturn('created');

        $this->assertSame('created', $service->createRecipe($data, $ingredients));
    }

    public function test_update_recipe_passes_data_and_ingredients(): void
    {
        $service = $this->makeService($recipeRepo);
        $data = ['name' => 'Barfi'];
        $ingredients = [['item_id' => 2, 'qty' => 3]];
        $recipeRepo->shouldReceive('update')->once()->with(5, $data, $ingredients)->andReturn('updated');

        $this->assertSame('updated', $service->updateRecipe(5, $data, $ingredients));
    }

    public function test_delete_recipe_delegates(): void
    {
        $service = $this->makeService($recipeRepo);
        $recipeRepo->shouldReceive('delete')->once()->with(5)->andReturn(true);

        $this->assertTrue($service->deleteRecipe(5));
    }

    public function test_get_finished_goods_filters_item_type(): void
    {
        $service = $this->makeService($recipeRepo, $itemRepo);
        $paginator = Mockery::mock();
        $paginator->shouldReceive('items')->once()->andReturn(['fg']);
        $itemRepo->shouldReceive('getAll')->once()->with(1000, ['item_type' => 'finished_good'])->andReturn($paginator);

        $this->assertSame(['fg'], $service->getFinishedGoods());
    }

    public function test_get_raw_materials_filters_item_type(): void
    {
        $service = $this->makeService($recipeRepo, $itemRepo);
        $paginator = Mockery::mock();
        $paginator->shouldReceive('items')->once()->andReturn(['rm']);
        $itemRepo->shouldReceive('getAll')->once()->with(1000, ['item_type' => 'raw_material'])->andReturn($paginator);

        $this->assertSame(['rm'], $service->getRawMaterials());
    }

    public function test_get_units_returns_paginator_items(): void
    {
        $service = $this->makeService($recipeRepo, $itemRepo, $unitRepo);
        $paginator = Mockery::mock();
        $paginator->shouldReceive('items')->once()->andReturn(['unit']);
        $unitRepo->shouldReceive('getAll')->once()->with(100)->andReturn($paginator);

        $this->assertSame(['unit'], $service->getUnits());
    }
}
