<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->nullable()->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->date('return_date');
            $table->decimal('total_amount', 10, 2);
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('return_date');
            $table->index('supplier_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_returns');
    }
};