<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'batch_qty', 'batch_unit_id', 'notes'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function batchUnit()
    {
        return $this->belongsTo(Unit::class, 'batch_unit_id');
    }

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function productionLogs()
    {
        return $this->hasMany(ProductionLog::class);
    }
}