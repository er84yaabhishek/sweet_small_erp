<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 30)->unique();
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->date('sale_date');
            $table->time('sale_time');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('round_off', 4, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->boolean('is_gst_invoice')->default(false);
            $table->enum('status', ['completed', 'returned', 'cancelled'])->default('completed');
            $table->string('note', 255)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('sale_date');
            $table->index('invoice_no');
            $table->index('customer_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};