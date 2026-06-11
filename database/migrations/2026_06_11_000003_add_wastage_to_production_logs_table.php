<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('production_logs', function (Blueprint $table) {
            $table->decimal('wastage_qty', 10, 3)->default(0)->after('qty_produced');
        });
    }

    public function down()
    {
        Schema::table('production_logs', function (Blueprint $table) {
            $table->dropColumn('wastage_qty');
        });
    }
};
