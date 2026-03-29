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
        Schema::create('unit_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->constrained()->onDelete('cascade');
            $table->string('type'); // departure or return
            $table->dateTime('check_date');
            $table->integer('km_reading')->nullable();
            $table->string('fuel_level')->nullable();
            $table->json('items')->nullable(); // Store physical check status
            $table->json('documents')->nullable(); // Store doc check status
            $table->text('notes')->nullable();
            $table->json('photos')->nullable();
            $table->foreignId('checker_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_checklists');
    }
};
