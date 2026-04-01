<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobOrder;
use App\Models\JobOrderPayment;
use App\Models\UnitMaintenanceLog;
use App\Models\Branch;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());
        $branchId = $request->get('branch_id');

        $isSuperAdmin = auth()->user()->role === 'super admin';

        // Revenue query
        $revenueQuery = JobOrderPayment::whereBetween('payment_date', [$startDate, $endDate]);
        if (!$isSuperAdmin) {
            $revenueQuery->where('branch_id', auth()->user()->branch_id);
        } elseif ($branchId) {
            $revenueQuery->where('branch_id', $branchId);
        }
        $totalRevenue = $revenueQuery->sum('amount');

        // Maintenance query
        $maintenanceQuery = UnitMaintenanceLog::whereBetween('service_date', [$startDate, $endDate]);
        if (!$isSuperAdmin) {
            $maintenanceQuery->where('branch_id', auth()->user()->branch_id);
        } elseif ($branchId) {
            $maintenanceQuery->where('branch_id', $branchId);
        }
        $totalMaintenance = $maintenanceQuery->sum('cost');

        // Job Orders query
        $ordersQuery = JobOrder::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        if (!$isSuperAdmin) {
            $ordersQuery->where('branch_id', auth()->user()->branch_id);
        } elseif ($branchId) {
            $ordersQuery->where('branch_id', $branchId);
        }
        $totalOrders = $ordersQuery->count();
        $closedOrders = (clone $ordersQuery)->where('is_closed', true)->count();

        // Monthly Data for Charts (Last 6 Months)
        $chartLabels = [];
        $revenueData = [];
        $maintenanceData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $chartLabels[] = $month->format('M Y');
            
            $mStart = $month->copy()->startOfMonth();
            $mEnd = $month->copy()->endOfMonth();

            $rev = JobOrderPayment::whereBetween('payment_date', [$mStart, $mEnd]);
            if (!$isSuperAdmin) $rev->where('branch_id', auth()->user()->branch_id);
            elseif ($branchId) $rev->where('branch_id', $branchId);
            $revenueData[] = $rev->sum('amount');

            $maint = UnitMaintenanceLog::whereBetween('service_date', [$mStart, $mEnd]);
            if (!$isSuperAdmin) $maint->where('branch_id', auth()->user()->branch_id);
            elseif ($branchId) $maint->where('branch_id', $branchId);
            $maintenanceData[] = $maint->sum('cost');
        }

        $branches = $isSuperAdmin ? Branch::all() : [];

        return view('reports.index', compact(
            'totalRevenue', 
            'totalMaintenance', 
            'totalOrders', 
            'closedOrders',
            'chartLabels',
            'revenueData',
            'maintenanceData',
            'startDate',
            'endDate',
            'branchId',
            'branches',
            'isSuperAdmin'
        ));
    }
}
