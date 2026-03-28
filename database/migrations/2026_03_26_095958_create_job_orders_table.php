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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('spj_number')->unique();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Petugas
            $table->string('sales_market')->nullable();
            $table->string('destination');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->date('return_date');
            $table->string('duration')->nullable();
            $table->text('description')->nullable();
            $table->enum('payment_status', ['Lunas', 'DP'])->default('DP');
            $table->decimal('price_per_day', 15, 2);
            $table->integer('days_count')->default(1);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_orders');
    }
};
