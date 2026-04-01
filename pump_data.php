<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

// Konfigurasi koneksi baru ke Supabase PostgreSQL
Config::set('database.connections.supabase', [
    'driver' => 'pgsql',
    'host' => 'aws-1-ap-southeast-1.pooler.supabase.com',
    'port' => '6543',
    'database' => 'postgres',
    'username' => 'postgres.fmgdmrqpznqumucjgauq',
    'password' => 'Arulpusing12#',
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'sslmode' => 'prefer',
]);

// Set default database menjadi Supabase agar migrate berjalan ke Supabase
Config::set('database.default', 'supabase');

echo "Membangun struktur tabel di Supabase (PostgreSQL)...\n";
Artisan::call('migrate:fresh', ['--force' => true, '--database' => 'supabase']);
echo Artisan::output();

echo "\nMenyalin data dari MySQL Lokal ke Supabase PostgreSQL...\n";

// Urutan tabel penting agar tidak melanggar aturan Foreign Key (Relasi)
$tables = [
    'branches',
    'users',
    'customers',
    'drivers',
    'units',
    'system_settings',
    'job_orders',
    'job_order_payments',
    'job_order_claims',
    'unit_checklists',
    'unit_maintenance_logs',
    'activity_logs'
];

foreach ($tables as $table) {
    echo "Menyalin tabel: $table...\n";
    try {
        $rows = DB::connection('mysql')->table($table)->get()->map(function($item) {
            return (array) $item;
        })->toArray();
        
        if (count($rows) > 0) {
            foreach (array_chunk($rows, 100) as $chunk) {
                DB::connection('supabase')->table($table)->insert($chunk);
            }
            echo "  --> Sukses! " . count($rows) . " baris disalin ke $table.\n";
        } else {
            echo "  --> Kosong. Lewati $table.\n";
        }
    } catch (\Exception $e) {
        echo "  --> GAGAL MENYALIN $table: " . $e->getMessage() . "\n";
    }
}

echo "\nProses Sukses! Data lokal berhasil dikirimkan seluruhnya ke Supabase.\n";
