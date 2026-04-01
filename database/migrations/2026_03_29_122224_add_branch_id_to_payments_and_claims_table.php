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
        Schema::table('job_order_payments', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('job_order_claims', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
        });

        // Data Migration kawan - Database agnostic way
        \Illuminate\Support\Facades\DB::update("
            UPDATE job_order_payments 
            SET branch_id = job_orders.branch_id 
            FROM job_orders 
            WHERE job_order_payments.job_order_id = job_orders.id 
            AND job_order_payments.branch_id IS NULL
        ");

        \Illuminate\Support\Facades\DB::update("
            UPDATE job_order_claims 
            SET branch_id = job_orders.branch_id 
            FROM job_orders 
            WHERE job_order_claims.job_order_id = job_orders.id 
            AND job_order_claims.branch_id IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_order_payments', function (Blueprint $table) {
            $table->dropForeign(['job_order_payments_branch_id_foreign']);
            $table->dropColumn('branch_id');
        });

        Schema::table('job_order_claims', function (Blueprint $table) {
            $table->dropForeign(['job_order_claims_branch_id_foreign']);
            $table->dropColumn('branch_id');
        });
    }
};
