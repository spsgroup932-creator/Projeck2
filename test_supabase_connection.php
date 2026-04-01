<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

Config::set('database.connections.supabase_test', [
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
    'sslmode' => 'require', // Changed to require for Supabase
]);

try {
    $result = DB::connection('supabase_test')->select('SELECT version()');
    echo "Koneksi Supabase BERHASIL!\n";
    print_r($result);
} catch (\Exception $e) {
    echo "Koneksi Supabase GAGAL: " . $e->getMessage() . "\n";
}
