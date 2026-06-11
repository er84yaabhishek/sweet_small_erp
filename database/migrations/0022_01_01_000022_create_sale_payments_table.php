<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->enum('payment_mode', ['cash', 'upi', 'card', 'credit']);
            $table->decimal('amount', 10, 2);
            $table->string('reference_no', 100)->nullable();
            $table->timestamps();

            $table->index('sale_id');
            $table->index('payment_mode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_payments');
    }
};