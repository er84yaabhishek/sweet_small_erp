<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    use HasFactory;

    protected $fillable = ['recipe_id', 'ingredient_item_id', 'qty_required', 'unit_id'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function ingredientItem()
    {
        return $this->belongsTo(Item::class, 'ingredient_item_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}