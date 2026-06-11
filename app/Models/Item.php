<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'category_id', 'unit_id', 'item_type',
        'sale_price', 'purchase_price', 'reorder_level', 'hsn_code',
        'tax_rate_id', 'barcode', 'image', 'is_sellable', 'is_purchasable', 'is_active'
    ];

    protected $casts = [
        'is_sellable' => 'boolean',
        'is_purchasable' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function stockLedgerEntries()
    {
        return $this->hasMany(StockLedger::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function recipeIngredients()
    {
        return $this->hasMany(RecipeIngredient::class, 'ingredient_item_id');
    }

    public function productionLogs()
    {
        return $this->hasMany(ProductionLog::class);
    }

    public function currentStock()
    {
        return $this->stockLedgerEntries()
            ->selectRaw('SUM(qty_in) - SUM(qty_out) as stock')
            ->value('stock') ?? 0;
    }
}