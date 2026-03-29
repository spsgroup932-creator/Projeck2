<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Driver;
use App\Models\JobOrder;
use App\Models\JobOrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = strtolower($user->role) === 'super admin';
        $branchId = $user->branch_id;

        // Basic Stats
        $queryUsers = User::query();
        $queryCustomers = Customer::query();
        $queryUnits = Unit::query();
        $queryDrivers = Driver::query();
        $queryJobOrders = JobOrder::query();

        if (!$isSuperAdmin) {
            $queryUsers->where('branch_id', $branchId);
            $queryCustomers->where('branch_id', $branchId);
            $queryUnits->where('branch_id', $branchId);
            $queryDrivers->where('branch_id', $branchId);
            $queryJobOrders->where('branch_id', $branchId);
        }

        $stats = [
            'total_staff' => $queryUsers->count(),
            'total_customers' => $queryCustomers->count(),
            'total_units' => $queryUnits->count(),
            'total_drivers' => $queryDrivers->count(),
            'active_orders' => $queryJobOrders->clone()->where('is_closed', false)->count(),
            'units_on_road' => $queryUnits->clone()->whereHas('jobOrders', function($q) {
                $q->where('is_closed', false);
            })->count(),
            'total_subscription_revenue' => \App\Models\Branch::sum('subscription_amount'),
        ];

        // Revenue Data (Last 6 Months)
        $revenueData = [];
        $maintenanceData = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->translatedFormat('F');
            
            // Revenue
            $revenueQuery = JobOrderPayment::whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month);
            
            // Maintenance
            $maintenanceQuery = \App\Models\UnitMaintenanceLog::whereYear('service_date', $month->year)
                ->whereMonth('service_date', $month->month);

            if (!$isSuperAdmin) {
                $revenueQuery->whereHas('jobOrder', function($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
                $maintenanceQuery->where('branch_id', $branchId);
            }
            
            $revenueData[] = (float) $revenueQuery->sum('amount');
            $maintenanceData[] = (float) $maintenanceQuery->sum('cost');
        }

        // Unit Utilization (Active vs Idle)
        $activeUnitsCount = $stats['units_on_road'];
        $idleUnitsCount = max(0, $stats['total_units'] - $activeUnitsCount);

        // Top 5 Customers
        $topCustomersQuery = Customer::withCount('jobOrders')
            ->orderBy('job_orders_count', 'desc')
            ->limit(5);

        if (!$isSuperAdmin) {
            $topCustomersQuery->where('branch_id', $branchId);
        }
        $topCustomers = $topCustomersQuery->get();

        // Branch-level component control kawan
        $showFinChart = $user->canAccessMenu('dash_financial_chart');
        $showUtilChart = $user->canAccessMenu('dash_utilization_chart');
        $showTopCustomers = $user->canAccessMenu('dash_top_customers');

        return view('dashboard', compact(
            'stats', 
            'labels', 
            'revenueData', 
            'maintenanceData',
            'activeUnitsCount',
            'idleUnitsCount',
            'topCustomers',
            'isSuperAdmin',
            'showFinChart',
            'showUtilChart',
            'showTopCustomers'
        ));
    }
}
