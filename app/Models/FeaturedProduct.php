<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedProduct extends Model
{
    protected $fillable = ['item_id', 'display_order', 'is_active'];
    public function item() { return $this->belongsTo(Item::class); }
}
