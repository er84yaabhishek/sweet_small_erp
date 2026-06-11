<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('sku', 50)->nullable()->unique();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->enum('item_type', ['raw_material', 'finished_good']);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('reorder_level', 10, 3)->nullable();
            $table->string('hsn_code', 20)->nullable();
            $table->foreignId('tax_rate_id')->nullable()->constrained();
            $table->string('barcode', 100)->nullable()->unique();
            $table->string('image', 255)->nullable();
            $table->boolean('is_sellable')->default(true);
            $table->boolean('is_purchasable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('sku');
            $table->index('item_type');
            $table->index('is_sellable');
            $table->index('is_purchasable');
            $table->index('is_active');
            $table->index('barcode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
};