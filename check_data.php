<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== JOB ORDERS ===\n";
$jos = App\Models\JobOrder::withoutGlobalScopes()->get();
foreach ($jos as $jo) {
    echo "ID={$jo->id} SPJ={$jo->spj_number} Status={$jo->payment_status} Total={$jo->total_price} Branch={$jo->branch_id}\n";
}
echo "Total: " . $jos->count() . "\n\n";

echo "=== PAYMENTS ===\n";
$payments = App\Models\JobOrderPayment::withoutGlobalScopes()->get();
foreach ($payments as $p) {
    echo "ID={$p->id} JO_ID={$p->job_order_id} Amount={$p->amount} Method={$p->method} Branch={$p->branch_id}\n";
}
echo "Total: " . $payments->count() . "\n\n";

echo "=== USERS ===\n";
$users = App\Models\User::withoutGlobalScopes()->get();
foreach ($users as $u) {
    echo "ID={$u->id} Name={$u->name} Role={$u->role} Branch={$u->branch_id}\n";
}
echo "Total: " . $users->count() . "\n";
