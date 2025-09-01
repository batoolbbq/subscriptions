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
        Schema::create('institucions', function (Blueprint $table) {

        $table->id();

        $table->string('name');

        $table->string('commercial_number')->nullable()->unique();
        $table->string('code', 50)->nullable()->after('name');


        $table->unsignedBigInteger('work_categories_id');
        $table->unsignedBigInteger('subscriptions_id');
        $table->unsignedBigInteger('insurance_agent_id');

        $table->integer('status')->default(0);

        $table->string('license_number')->nullable();
        $table->string('commercial_record')->nullable();

        $table->timestamps();

        $table->foreign('work_categories_id')->references('id')->on('work_categories');
        $table->foreign('subscriptions_id')->references('id')->on('subscription33');
        $table->foreign('insurance_agent_id')->references('id')->on('insurance_agents');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institucions');
                $table->dropColumn('code');

    }
};
