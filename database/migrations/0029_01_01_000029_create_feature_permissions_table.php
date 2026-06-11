<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feature_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('feature_name', 100)->unique();
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();

            $table->index('feature_name');
            $table->index('is_enabled');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feature_permissions');
    }
};