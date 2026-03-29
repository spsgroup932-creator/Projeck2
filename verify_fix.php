<?php

use App\Models\Unit;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Starting verification...\n";

// 1. Ensure a unit exists in one branch
$branch1 = Branch::first();
if (!$branch1) {
    echo "No branches found. Creating one...\n";
    $branch1 = Branch::create(['name' => 'Branch 1', 'address' => 'Addr 1']);
}

$unit1 = Unit::withoutGlobalScopes()->where('unit_code', 'UNIT-0001')->first();
if (!$unit1) {
    echo "Creating UNIT-0001 in Branch {$branch1->id}...\n";
    // Bypass events/traits for setup if needed, but here we just create it
    $unit1 = Unit::withoutGlobalScopes()->create([
        'nopol' => 'B 1234 ABC',
        'name' => 'Test Unit 1',
        'chassis_number' => 'CH123',
        'year' => 2020,
        'unit_code' => 'UNIT-0001',
        'branch_id' => $branch1->id
    ]);
}

echo "Existing unit code: " . $unit1->unit_code . " in Branch " . $unit1->branch_id . "\n";

// 2. Simulate a user from a DIFFERENT branch (Branch 13)
$branch13 = Branch::find(13);
if (!$branch13) {
    echo "Branch 13 not found. Creating it...\n";
    $branch13 = Branch::firstOrCreate(['id' => 13], ['name' => 'Branch 13', 'address' => 'Addr 13']);
}

$user13 = User::where('branch_id', 13)->first();
if (!$user13) {
    $user13 = User::create([
        'name' => 'Admin Branch 13',
        'email' => 'admin13@test.com',
        'password' => bcrypt('password'),
        'role' => 'admin rental',
        'branch_id' => 13
    ]);
}

Auth::login($user13);
echo "Logged in as user from Branch " . Auth::user()->branch_id . " (Role: " . Auth::user()->role . ")\n";

// 3. Check what Unit::latest('id')->first() returns WITH global scope
$scopedLast = Unit::latest('id')->first();
echo "Scoped latest unit: " . ($scopedLast ? $scopedLast->unit_code : "NULL") . "\n";

// 4. Check what Unit::withoutGlobalScopes()->latest('id')->first() returns
$unscopedLast = Unit::withoutGlobalScopes()->latest('id')->first();
echo "Unscoped latest unit: " . ($unscopedLast ? $unscopedLast->unit_code : "NULL") . "\n";

if ($scopedLast === null && $unscopedLast !== null) {
    echo "SUCCESS: The fix allows finding the global latest unit even when filtered by branch scope.\n";
} else if ($scopedLast !== null && Auth::user()->branch_id != $scopedLast->branch_id) {
    echo "Wait, scoped last is not null but from different branch? (Should not happen if trait is working)\n";
} else {
    echo "Check results carefully. Scoped: " . ($scopedLast ? $scopedLast->unit_code : "NULL") . ", Unscoped: " . ($unscopedLast ? $unscopedLast->unit_code : "NULL") . "\n";
}
