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
    Schema::table('customers', function (Blueprint $table) {
        $table->unsignedBigInteger('bank_id')->nullable()->after('cities_id');
        $table->unsignedBigInteger('bank_branch_id')->nullable()->after('bank_id');
        $table->unsignedBigInteger('institucion_sheet_row_id')->nullable()->after('bank_branch_id');

        $table->foreign('bank_id')->references('id')->on('banks')->nullOnDelete();
        $table->foreign('bank_branch_id')->references('id')->on('bank_branches')->nullOnDelete();

        $table->index(['bank_id', 'bank_branch_id']);
    });
}

public function down(): void
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropForeign(['bank_id']);
        $table->dropForeign(['bank_branch_id']);
        $table->dropIndex(['bank_id', 'bank_branch_id']);
        $table->dropColumn(['bank_id', 'bank_branch_id']);
    });
}
};