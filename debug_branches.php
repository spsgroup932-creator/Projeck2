<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Branch;

$branches = Branch::all();
echo "Listing All Branches and their Menus:\n";
echo str_repeat("-", 60) . "\n";
foreach ($branches as $branch) {
    $menus = is_array($branch->accessible_menus) ? implode(', ', $branch->accessible_menus) : 'NONE';
    echo "Name: {$branch->name}\n";
    echo "Menus: {$menus}\n";
    echo "Amount: " . number_format($branch->subscription_amount) . "\n";
    echo str_repeat("-", 60) . "\n";
}
