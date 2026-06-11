<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->enum('type', ['finished_good', 'raw_material']); // as per final design
            $table->timestamps();

            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};