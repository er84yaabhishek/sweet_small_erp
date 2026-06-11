<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_item_id')->constrained('items');
            $table->decimal('qty_required', 10, 3);
            $table->foreignId('unit_id')->constrained();
            $table->timestamps();

            $table->index('recipe_id');
            $table->index('ingredient_item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};