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
        Schema::create('institucion_sheet_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institucion_id')->constrained('institucions')->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('national_id', 50)->nullable();
            $table->string('family_registry_no', 50)->nullable();
            $table->string('account_no', 100)->nullable();
            $table->timestamps();

            // فهارس مفيدة للبحث
            $table->index(['national_id', 'account_no']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institucion_sheet_rows');
    }
};
