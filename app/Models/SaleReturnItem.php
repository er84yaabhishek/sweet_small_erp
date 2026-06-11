<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturnItem extends Model
{
    use HasFactory;

    protected $fillable = ['sale_return_id', 'item_id', 'qty', 'unit_price', 'line_total', 'reason'];

    public function saleReturn()
    {
        return $this->belongsTo(SaleReturn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}