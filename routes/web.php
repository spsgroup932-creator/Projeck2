<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BranchController;

Route::get('/', function () {
    return redirect()->route('login');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
 
require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:super admin,admin cabang'])->group(function () {
    // Route::resource('users', UserController::class); // This line was removed as per the instruction's implied change
});

// -- Resource Routes --
Route::middleware(['auth', 'verified'])->group(function () {
    // Management Cabang & User
    Route::resource('branches', BranchController::class)->middleware('role:super admin');
    Route::resource('users', UserController::class); // Moved here from the 'role' middleware group
    Route::resource('customers', CustomerController::class);
    Route::resource('units', UnitController::class);
    Route::resource('drivers', DriverController::class);
    Route::get('job-orders/closed', [JobOrderController::class, 'closed'])->name('job-orders.closed');
    Route::get('job-orders/closed/{job_order}', [JobOrderController::class, 'showClosed'])->name('job-orders.show-closed');
    Route::get('job-orders/{job_order}/print', [JobOrderController::class, 'print'])->name('job-orders.print');
    Route::get('job-orders/{job_order}/download-pdf', [JobOrderController::class, 'downloadPdf'])->name('job-orders.download-pdf');
    Route::get('job-orders/{job_order}/receipt', [JobOrderController::class, 'receipt'])->name('job-orders.receipt');
    
    // Payment Module
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/transactions', [PaymentController::class, 'transactions'])->name('payments.transactions');
    Route::get('payments/settled-report', [PaymentController::class, 'settledReport'])->name('payments.settled_report');
    Route::get('payments/claims', [PaymentController::class, 'claims'])->name('payments.claims');
    Route::post('payments/{job_order}/settle', [PaymentController::class, 'settle'])->name('payments.settle');
    Route::post('payments/{job_order}/unsettle', [PaymentController::class, 'unsettle'])->name('payments.unsettle');
    Route::get('payments/{payment}/download', [PaymentController::class, 'downloadPaymentReceipt'])->name('payments.download');
    Route::get('claims/{claim}/download', [PaymentController::class, 'downloadClaimReceipt'])->name('claims.download');

    Route::get('job-orders/report', [JobOrderController::class, 'report'])->name('job-orders.report');
    Route::get('job-orders/export', [JobOrderController::class, 'export'])->name('job-orders.export');
    Route::resource('job-orders', JobOrderController::class);

    Route::post('job-orders/{job_order}/payment', [JobOrderController::class, 'addPayment'])->name('job-orders.payment');
    Route::post('job-orders/{job_order}/claim', [JobOrderController::class, 'addClaim'])->name('job-orders.claim');
    Route::post('job-orders/{job_order}/close', [JobOrderController::class, 'closeOrder'])->name('job-orders.close');
    Route::post('job-orders/{job_order}/unclose', [JobOrderController::class, 'unclose'])->name('job-orders.unclose');
    
    Route::resource('maintenance', \App\Http\Controllers\UnitMaintenanceController::class);
    
    // Placeholder untuk laporan
    Route::get('/reports', function() { return '<h3>Halaman Laporan Bekerja!</h3>'; })->name('reports.index');
});
