<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('gstin', 20)->nullable();
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->timestamps();

            $table->index('name');
            $table->index('phone');
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
};