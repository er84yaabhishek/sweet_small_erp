<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained();
            $table->foreignId('item_id')->constrained(); // finished good
            $table->decimal('batches', 5, 2);
            $table->decimal('qty_produced', 10, 3);
            $table->date('production_date');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('production_date');
            $table->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_logs');
    }
};