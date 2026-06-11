<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained(); // finished good
            $table->decimal('batch_qty', 10, 3);
            $table->foreignId('batch_unit_id')->constrained('units');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('recipes');
    }
};