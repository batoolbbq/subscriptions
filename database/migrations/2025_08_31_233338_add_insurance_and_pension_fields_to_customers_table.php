<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('insured_no', 255)->nullable()->after('bank_id');
            $table->string('pension_no', 255)->nullable()->after('insured_no');
            $table->string('account_no', 100)->unique()->after('pension_no');
            $table->decimal('total_pension', 12, 2)->default(0)->after('account_no');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['insured_no', 'pension_no', 'account_no', 'total_pension']);
        });
    }
};
