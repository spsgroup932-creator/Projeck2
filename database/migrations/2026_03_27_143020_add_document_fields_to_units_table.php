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
        Schema::table('units', function (Blueprint $table) {
            if (!Schema::hasColumn('units', 'stnk_expiry')) {
                $table->date('stnk_expiry')->nullable();
            }
            if (!Schema::hasColumn('units', 'kir_expiry')) {
                $table->date('kir_expiry')->nullable();
            }
            if (!Schema::hasColumn('units', 'document_path')) {
                $table->string('document_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['stnk_expiry', 'kir_expiry', 'document_path']);
        });
    }
};
