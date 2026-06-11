<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['cash', 'bank', 'income', 'expense', 'supplier', 'customer']);
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->timestamps();

            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};