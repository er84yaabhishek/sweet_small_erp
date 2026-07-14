<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rate', 'cgst', 'sgst', 'igst'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
