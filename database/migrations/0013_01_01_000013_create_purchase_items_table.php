<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained();
            $table->decimal('qty', 10, 3);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('line_total', 10, 2);
            $table->timestamps();

            $table->index('purchase_id');
            $table->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_items');
    }
};