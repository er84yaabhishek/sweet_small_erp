<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['customer_name', 'customer_image', 'message', 'rating', 'is_active', 'display_order'];
}
