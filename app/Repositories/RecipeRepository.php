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
        return DB::transaction(function () use ($data, $ingredients) {
            $recipe = Recipe::create($data);
            $this->createIngredients($recipe->id, $ingredients);

            return $recipe;
        });
    }

    public function update($id, array $data, array $ingredients)
    {
        return DB::transaction(function () use ($id, $data, $ingredients) {
            $recipe = $this->findById($id);
            $recipe->update($data);
            $recipe->ingredients()->delete();
            $this->createIngredients($recipe->id, $ingredients);

            return $recipe;
        });
    }

    public function delete($id)
    {
        $recipe = $this->findById($id);
        return $recipe->delete();
    }

    private function createIngredients($recipeId, array $ingredients)
    {
        foreach ($ingredients as $ingredient) {
            RecipeIngredient::create([
                'recipe_id' => $recipeId,
                'ingredient_item_id' => $ingredient['ingredient_item_id'],
                'qty_required' => $ingredient['qty_required'],
                'unit_id' => $ingredient['unit_id'],
            ]);
        }
    }
}