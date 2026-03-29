<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Branch;

$majorMenus = ['master_data', 'job_order', 'pembayaran', 'maintenance'];
// Also include dashboard components by default to make it look active
$extraMenus = ['dash_financial_chart', 'dash_utilization_chart', 'dash_top_customers'];
$allFeatures = array_merge($majorMenus, $extraMenus);

echo "Starting mass feature sync for ALL branches kawan...\n";

foreach (Branch::all() as $branch) {
    $branch->update([
        'accessible_menus' => $allFeatures,
        'subscription_amount' => count($majorMenus) * 50000 // 200,000
    ]);
    echo "Sikat: {$branch->name} -> 4 Features enabled (Rp 200,000)\n";
}

echo "Mass sync completed! Dashboard should be full of cuan now kawan!\n";
