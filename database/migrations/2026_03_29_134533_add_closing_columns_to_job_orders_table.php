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
            if (!Schema::hasColumn('job_orders', 'closing_number')) {
                $table->string('closing_number')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('job_orders', 'closing_date')) {
                $table->date('closing_date')->nullable()->after('closing_number');
            }
            if (!Schema::hasColumn('job_orders', 'is_closed')) {
                $table->boolean('is_closed')->default(false)->after('closing_date');
            }
            if (!Schema::hasColumn('job_orders', 'digital_signature')) {
                $table->longText('digital_signature')->nullable()->after('is_closed');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropColumn(['closing_number', 'closing_date', 'is_closed', 'digital_signature']);
        });
    }
};
