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

        // Data Migration kawan
        \Illuminate\Support\Facades\DB::statement("
            UPDATE job_order_payments p 
            JOIN job_orders j ON p.job_order_id = j.id
            SET p.branch_id = j.branch_id
            WHERE p.branch_id IS NULL
        ");

        \Illuminate\Support\Facades\DB::statement("
            UPDATE job_order_claims c 
            JOIN job_orders j ON c.job_order_id = j.id
            SET c.branch_id = j.branch_id
            WHERE c.branch_id IS NULL
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
