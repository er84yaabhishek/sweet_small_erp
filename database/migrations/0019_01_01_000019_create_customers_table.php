<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('phone', 15)->nullable()->unique();
            $table->string('gstin', 20)->nullable();
            $table->timestamps();

            $table->index('phone');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};