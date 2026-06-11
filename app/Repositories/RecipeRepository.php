<?php

namespace App\Repositories;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Support\Facades\DB;

class RecipeRepository
{
    public function getAll($perPage = 20)
    {
        return Recipe::with(['item', 'batchUnit'])->paginate($perPage);
    }

    public function findById($id)
    {
        return Recipe::with(['item', 'batchUnit', 'ingredients.ingredientItem', 'ingredients.unit'])->findOrFail($id);
    }

    public function create(array $data, array $ingredients)
    {
        DB::beginTransaction();
        try {
            $recipe = Recipe::create($data);
            foreach ($ingredients as $ing) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'ingredient_item_id' => $ing['ingredient_item_id'],
                    'qty_required' => $ing['qty_required'],
                    'unit_id' => $ing['unit_id'],
                ]);
            }
            DB::commit();
            return $recipe;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, array $data, array $ingredients)
    {
        DB::beginTransaction();
        try {
            $recipe = $this->findById($id);
            $recipe->update($data);
            // Delete existing ingredients and recreate
            $recipe->ingredients()->delete();
            foreach ($ingredients as $ing) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'ingredient_item_id' => $ing['ingredient_item_id'],
                    'qty_required' => $ing['qty_required'],
                    'unit_id' => $ing['unit_id'],
                ]);
            }
            DB::commit();
            return $recipe;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $recipe = $this->findById($id);
        return $recipe->delete();
    }
}