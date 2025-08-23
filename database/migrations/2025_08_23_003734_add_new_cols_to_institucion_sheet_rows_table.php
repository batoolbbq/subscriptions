<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('institucion_sheet_rows', function (Blueprint $table) {
            $table->string('father_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('father_name');
            $table->string('insured_no')->nullable()->after('family_registry_no');
            $table->string('pension_no')->nullable()->after('insured_no');
            $table->decimal('total_pension', 12, 2)->nullable()->after('account_no');
        });
    }

    public function down(): void
    {
        Schema::table('institucion_sheet_rows', function (Blueprint $table) {
            $table->dropColumn(['father_name','last_name','insured_no','pension_no','total_pension']);
        });
    }
};

