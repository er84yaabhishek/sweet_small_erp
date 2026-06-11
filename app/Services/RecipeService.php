<?php

namespace App\Services;

use App\Repositories\RecipeRepository;
use App\Repositories\ItemRepository;
use App\Repositories\UnitRepository;

class RecipeService
{
    protected $recipeRepo;
    protected $itemRepo;
    protected $unitRepo;

    public function __construct(RecipeRepository $recipeRepo, ItemRepository $itemRepo, UnitRepository $unitRepo)
    {
        $this->recipeRepo = $recipeRepo;
        $this->itemRepo = $itemRepo;
        $this->unitRepo = $unitRepo;
    }

    public function getAllRecipes($perPage = 20)
    {
        return $this->recipeRepo->getAll($perPage);
    }

    public function getRecipeById($id)
    {
        return $this->recipeRepo->findById($id);
    }

    public function createRecipe(array $data, array $ingredients)
    {
        return $this->recipeRepo->create($data, $ingredients);
    }

    public function updateRecipe($id, array $data, array $ingredients)
    {
        return $this->recipeRepo->update($id, $data, $ingredients);
    }

    public function deleteRecipe($id)
    {
        return $this->recipeRepo->delete($id);
    }

    public function getFinishedGoods()
    {
        return $this->itemRepo->getAll(1000, ['item_type' => 'finished_good'])->items();
    }

    public function getRawMaterials()
    {
        return $this->itemRepo->getAll(1000, ['item_type' => 'raw_material'])->items();
    }

    public function getUnits()
    {
        return $this->unitRepo->getAll(100)->items();
    }
}
