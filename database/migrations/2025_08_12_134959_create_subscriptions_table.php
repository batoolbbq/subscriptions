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
        Schema::create('subscription33', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('beneficiaries_categories_id')->constrained('beneficiaries_categories');
            $table->enum('status', ['0', '1', '2', '3']);
             $table->foreignId('payment_due_type_id')
                  ->constrained('payment_due_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription33');
    }
};
