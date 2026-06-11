<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->string('category', 100);
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_mode', ['cash', 'upi', 'card', 'bank']);
            $table->string('reference_no', 100)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('expense_date');
            $table->index('category');
            $table->index('payment_mode');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};