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
        Schema::create('job_order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_order_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('method'); // Cash, Transfer
            $table->date('payment_date');
            $table->foreignId('user_id')->constrained(); // Staff recorded this
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_order_payments');
    }
};
