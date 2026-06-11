<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained();
            $table->enum('txn_type', [
                'opening', 'purchase', 'production_in', 'production_out',
                'sale', 'sale_return', 'purchase_return', 'adjustment'
            ]);
            $table->string('reference_type', 50)->nullable(); // e.g., 'purchase', 'sale', 'production_log'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('qty_in', 10, 3)->default(0);
            $table->decimal('qty_out', 10, 3)->default(0);
            $table->decimal('rate', 10, 2)->nullable();
            $table->string('note', 255)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['item_id', 'created_at']);
            $table->index('txn_type');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_ledger');
    }
};