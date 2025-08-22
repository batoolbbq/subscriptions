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
       Schema::create('subscription_values', function (Blueprint $table) {
        $table->id();
        $table->foreignId('subscription_type')->constrained('subscription_types'); // subscriptionType → KValue
        $table->decimal('value', 10, 2)->nullable();
        $table->boolean('is_percentage')->default(false);
        $table->integer('duration')->nullable();
        $table->foreignId('subscription_id')->constrained('subscription');
        $table->integer('status'); // EntityStatus
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_values');
    }
};
