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
            $table->date('stnk_expiry')->nullable()->after('year');
            $table->date('kir_expiry')->nullable()->after('stnk_expiry');
            $table->string('document_path')->nullable()->after('kir_expiry');
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
