<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('customers', function (Blueprint $table) {
        $table->unsignedBigInteger('institucion_id')->nullable()->after('cities_id');

        // ربط المفتاح الأجنبي
        $table->foreign('institucion_id')->references('id')->on('institucions');
    });
}

public function down()
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropForeign(['institucion_id']);
        $table->dropColumn('institucion_id');
    });
}

};
