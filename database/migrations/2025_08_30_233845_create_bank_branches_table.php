<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
{
    Schema::create('bank_branches', function (Blueprint $table) {
        $table->unsignedBigInteger('id')->primary();
        $table->unsignedBigInteger('bank_id'); // يربط الفرع بالمصرف
        $table->string('name');
        $table->timestamps();

        $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
        $table->index('bank_id');
    });
}
public function down(): void
{
    Schema::dropIfExists('bank_branches');
}
};