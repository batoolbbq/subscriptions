<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('insurance_agents', function (Blueprint $table) {
            if (!Schema::hasColumn('insurance_agents', 'agent_code')) {
                $table->string('agent_code', 50)->unique()->nullable()->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('insurance_agents', function (Blueprint $table) {
            if (Schema::hasColumn('insurance_agents', 'agent_code')) {
                $table->dropColumn('agent_code');
            }
        });
    }
};
