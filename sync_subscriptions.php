<?php

use App\Models\Branch;

// Load Laravel Bootstrap
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting branch subscription sync...\n";

$majorMenus = ['master_data', 'job_order', 'pembayaran', 'maintenance'];
$branches = Branch::all();

foreach ($branches as $branch) {
    $menus = is_array($branch->accessible_menus) ? $branch->accessible_menus : [];
    $count = count($menus);
    $amount = $count * 50000;
    
    $branch->update(['subscription_amount' => $amount]);
    echo "Updated {$branch->name} (#{$branch->code}): {$count} features -> Rp " . number_format($amount) . "\n";
}

echo "Sync completed kawan!\n";
