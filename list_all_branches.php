<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (App\Models\Branch::all() as $b) {
    echo $b->name . " | Menus: " . (is_array($b->accessible_menus) ? count($b->accessible_menus) : 'EMPTY') . " | Amount: " . $b->subscription_amount . "\n";
}
