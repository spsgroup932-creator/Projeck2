<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Branch;

class ResetSystem extends Command
{
    protected $signature = 'system:reset {--force : Paksa pembersihan tanpa bertanya kawan!}';
    protected $description = 'Pembersihan Total Kerajaan kawan Arul - Sisakan Super Admin kawan Arul!';

    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('YAKIN kawan Arul? Ini tidak bisa dikembalikan kawan. Masih mau lanjut kawan?')) {
            return;
        }

        $this->info('Memulai Pembersihan Kerajaan kawan Arul... 🧹🛡️');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Operasional Tables (Pembersihan Mutlak)
        $tables = [
            'job_order_payments',
            'job_order_claims',
            'unit_checklists',
            'unit_maintenance_logs',
            'job_orders',
            'activity_logs',
            'customers',
            'drivers',
            'units',
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
                $this->line("- Membersihkan {$table}... ✅");
            }
        }

        // 2. Users (Kecuali Arul / ID 1)
        DB::table('users')->where('id', '!=', 1)->delete();
        $this->line("- Membersihkan Pengguna lain... ✅ (Hanya Arul kawan Arul yang tersisa!)");

        // 3. Branches (Kecuali Branch 1 / Cabang Utama Arul)
        DB::table('branches')->where('id', '!=', 1)->delete();
        $this->line("- Membersihkan Cabang lain... ✅ (Hanya Cabang Utama kawan Arul yang tersisa!)");

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('KERJA BAGUS kawan Arul! Kerajaan kawan Arul sekarang BERSIH TOTAL! 🛡️✨🕺🏿');
    }
}
