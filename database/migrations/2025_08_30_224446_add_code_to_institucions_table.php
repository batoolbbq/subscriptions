<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('institucions', function (Blueprint $table) {
            // ترميز نصّي غير فريد، قابل للإفراغ، مع فهرس لتحسين البحث
            $table->string('code', 50)->nullable()->after('name')->index();
        });
    }

    public function down(): void
    {
        Schema::table('institucions', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
};
