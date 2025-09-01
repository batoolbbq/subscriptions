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
            // حذف العمود مباشرة لو موجود
            if (Schema::hasColumn('customers', 'institucion_sheet_row_id')) {
                $table->dropColumn('institucion_sheet_row_id');
            }

            // إضافة عمود IBAN لو مش موجود
            if (!Schema::hasColumn('customers', 'iban')) {
                $table->string('iban', 34)->nullable()->after('bank_branch_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // استرجاع العمود لو مش موجود
            if (!Schema::hasColumn('customers', 'institucion_sheet_row_id')) {
                $table->unsignedBigInteger('institucion_sheet_row_id')->nullable();
            }

            // حذف عمود IBAN لو موجود
            if (Schema::hasColumn('customers', 'iban')) {
                $table->dropColumn('iban');
            }
        });
    }
};
