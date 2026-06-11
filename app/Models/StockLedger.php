<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    use HasFactory;

    protected $table = 'stock_ledger';
    protected $fillable = [
        'item_id', 'txn_type', 'reference_type', 'reference_id',
        'qty_in', 'qty_out', 'rate', 'note', 'created_by'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}