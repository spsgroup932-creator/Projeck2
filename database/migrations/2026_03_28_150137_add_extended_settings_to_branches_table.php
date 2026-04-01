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
        Schema::table('branches', function (Blueprint $table) {
            if (!Schema::hasColumn('branches', 'whatsapp_number')) {
                $table->string('whatsapp_number')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('branches', 'email')) {
                $table->string('email')->nullable()->after('whatsapp_number');
            }
            if (!Schema::hasColumn('branches', 'website')) {
                $table->string('website')->nullable()->after('email');
            }
            if (!Schema::hasColumn('branches', 'watermark')) {
                $table->string('watermark')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('branches', 'nib')) {
                $table->string('nib')->nullable()->after('watermark');
            }
            if (!Schema::hasColumn('branches', 'npwp')) {
                $table->string('npwp')->nullable()->after('nib');
            }
            if (!Schema::hasColumn('branches', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('npwp');
            }
            if (!Schema::hasColumn('branches', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('branches', 'bank_account_name')) {
                $table->string('bank_account_name')->nullable()->after('bank_account_number');
            }
            if (!Schema::hasColumn('branches', 'receipt_footer')) {
                $table->text('receipt_footer')->nullable()->after('bank_account_name');
            }
            if (!Schema::hasColumn('branches', 'watermark_text')) {
                $table->string('watermark_text')->nullable()->after('receipt_footer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_number',
                'email',
                'website',
                'watermark',
                'nib',
                'npwp',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
                'receipt_footer',
                'watermark_text'
            ]);
        });
    }
};
