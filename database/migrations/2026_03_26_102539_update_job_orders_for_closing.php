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
        Schema::table('job_orders', function (Blueprint $table) {
            $table->string('closing_number')->nullable()->after('payment_status');
            $table->date('closing_date')->nullable()->after('closing_number');
            $table->boolean('is_closed')->default(false)->after('closing_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropColumn(['closing_number', 'closing_date', 'is_closed']);
        });
    }
};
