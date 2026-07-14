<?php

namespace App\Http\Controllers;

use App\Services\RecipeService;
use App\Http\Requests\RecipeRequest;

class RecipeController extends Controller
{
    protected $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
        $this->middleware('permission:view_recipes')->only(['index', 'show']);
        $this->middleware('permission:create_recipe')->only(['create', 'store']);
        $this->middleware('permission:edit_recipe')->only(['edit', 'update']);
        $this->middleware('permission:delete_recipe')->only('destroy');
    }

    public function index()
    {
        $recipes = $this->recipeService->getAllRecipes(20);
        return view('recipes.index', compact('recipes'));
    }

    public function create()
    {
        $finishedGoods = $this->recipeService->getFinishedGoods();
        $rawMaterials = $this->recipeService->getRawMaterials();
        $units = $this->recipeService->getUnits();
        return view('recipes.create', compact('finishedGoods', 'rawMaterials', 'units'));
    }

    public function store(RecipeRequest $request)
    {
        $ingredients = $this->decodeJsonArray($request, 'ingredients_json');
        if (empty($ingredients)) {
            return back()->withErrors('At least one ingredient required.');
        }
        $this->recipeService->createRecipe($request->validated(), $ingredients);
        return redirect()->route('recipes.index')->with('success', 'Recipe created successfully.');
    }

    public function show($id)
    {
        $recipe = $this->recipeService->getRecipeById($id);
        return view('recipes.show', compact('recipe'));
    }

    public function edit($id)
    {
        $recipe = $this->recipeService->getRecipeById($id);
        $finishedGoods = $this->recipeService->getFinishedGoods();
        $rawMaterials = $this->recipeService->getRawMaterials();
        $units = $this->recipeService->getUnits();
        return view('recipes.edit', compact('recipe', 'finishedGoods', 'rawMaterials', 'units'));
    }

    public function update(RecipeRequest $request, $id)
    {
        $ingredients = $this->decodeJsonArray($request, 'ingredients_json');
        $this->recipeService->updateRecipe($id, $request->validated(), $ingredients);
        return redirect()->route('recipes.index')->with('success', 'Recipe updated successfully.');
    }

    public function destroy($id)
    {
        $this->recipeService->deleteRecipe($id);
        return redirect()->route('recipes.index')->with('success', 'Recipe deleted.');
    }
}
