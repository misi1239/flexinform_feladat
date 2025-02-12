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
        Schema::create('services', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('car_id');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->string('log_number');
            $table->unique(['client_id', 'car_id', 'log_number']);
            $table->string('event');
            $table->timestamp('event_time')->nullable();
            $table->string('document_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
