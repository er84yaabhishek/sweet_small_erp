<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->nullable()->constrained();
            $table->foreignId('customer_id')->nullable()->constrained(); // nullable for walk-in
            $table->date('return_date');
            $table->decimal('total_amount', 10, 2);
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('return_date');
            $table->index('sale_id');
            $table->index('customer_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_returns');
    }
};