<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('expense_categories')->insert([
            ['name' => 'Rent', 'created_at' => now()],
            ['name' => 'Electricity', 'created_at' => now()],
            ['name' => 'Water', 'created_at' => now()],
            ['name' => 'Salary', 'created_at' => now()],
            ['name' => 'Repair & Maintenance', 'created_at' => now()],
            ['name' => 'Marketing', 'created_at' => now()],
            ['name' => 'Packing Material', 'created_at' => now()],
            ['name' => 'Transport', 'created_at' => now()],
            ['name' => 'Other', 'created_at' => now()],
        ]);

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('expense_category_id')->nullable()->after('category')->constrained('expense_categories');
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['expense_category_id']);
            $table->dropColumn('expense_category_id');
        });
        Schema::dropIfExists('expense_categories');
    }
};
