<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('user_id');

            $table->unsignedInteger('customer_id')->nullable();

            $table->unsignedBigInteger('service_id');

            $table->unsignedBigInteger('institucion_id')->nullable();

            $table->timestamps();

         

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('service_id')->references('id')->on('added_service_services');
            $table->foreign('institucion_id')->references('id')->on('institucions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_logs');
    }
};
