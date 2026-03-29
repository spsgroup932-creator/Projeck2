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
            $table->string('whatsapp_number')->nullable()->after('phone');
            $table->string('email')->nullable()->after('whatsapp_number');
            $table->string('website')->nullable()->after('email');
            $table->string('watermark')->nullable()->after('logo');
            $table->string('nib')->nullable()->after('watermark');
            $table->string('npwp')->nullable()->after('nib');
            $table->string('bank_name')->nullable()->after('npwp');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
            $table->text('receipt_footer')->nullable()->after('bank_account_name');
            $table->string('watermark_text')->nullable()->after('receipt_footer');
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
