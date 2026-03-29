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
        $tables = ['users', 'customers', 'units', 'drivers', 'job_orders'];

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'branch_id')) {
                Schema::table($table, function (Blueprint $tableBlueprint) {
                    $tableBlueprint->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['users', 'customers', 'units', 'drivers', 'job_orders'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                $tableBlueprint->dropForeign([$table . '_branch_id_foreign']); // Laravel naming convention
                $tableBlueprint->dropColumn('branch_id');
            });
        }
    }
};
