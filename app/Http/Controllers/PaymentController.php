<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\JobOrderPayment;
use App\Models\JobOrderClaim;
use App\Models\Customer;
// use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = JobOrder::query()->with(['customer', 'unit', 'driver', 'payments', 'claims'])
                        ->where('payment_status', '!=', 'Lunas');

        // Filter kawan
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('departure_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('departure_date', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('spj_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $jobOrders = $query->latest('departure_date')->paginate(20)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        return view('payments.index', compact('jobOrders', 'customers'));
    }

    public function transactions(Request $request)
    {
        $query = JobOrderPayment::query()->with(['jobOrder.customer', 'jobOrder.unit']);

        if ($request->filled('customer_id')) {
            $query->whereHas('jobOrder', function($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
        }

        $date_from = $request->input('date_from') ?: now()->startOfMonth()->format('Y-m-d');
        $date_to = $request->input('date_to') ?: now()->endOfMonth()->format('Y-m-d');

        $query->whereDate('payment_date', '>=', $date_from);
        $query->whereDate('payment_date', '<=', $date_to);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('jobOrder', function($q) use ($search) {
                $q->where('spj_number', 'like', "%{$search}%");
            });
        }

        // Clone query for exports kawan
        $allPayments = $query->orderBy('payment_date', 'asc')->get();
        
        $cashPayments = $allPayments->where('method', 'CASH');
        $transferPayments = $allPayments->where('method', 'Transfer');

        $totalAll = $allPayments->sum('amount');
        $totalCash = $cashPayments->sum('amount');
        $totalTransfer = $transferPayments->sum('amount');
        
        $customers = Customer::orderBy('name')->get();

        return view('payments.transactions', compact(
            'allPayments', 'cashPayments', 'transferPayments', 
            'customers', 'totalAll', 'totalCash', 'totalTransfer',
            'date_from', 'date_to'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = JobOrderPayment::query()->with(['jobOrder.customer', 'jobOrder.unit']);
        
        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }
        
        // Include existing filters kawan
        if ($request->filled('customer_id')) {
            $query->whereHas('jobOrder', function($q) use ($request) {
                $q->where('customer_id', $request->input('customer_id'));
            });
        }
        
        $date_from = $request->input('date_from') ?: now()->startOfMonth()->format('Y-m-d');
        $date_to = $request->input('date_to') ?: now()->endOfMonth()->format('Y-m-d');
        
        $query->whereDate('payment_date', '>=', $date_from);
        $query->whereDate('payment_date', '<=', $date_to);

        $payments = $query->orderBy('payment_date', 'asc')->get();
        $title = "LAPORAN TRANSAKSI " . strtoupper($request->input('method') ?? 'KESELURUHAN');
        $total = $payments->sum('amount');
        $periode = date('d/m/Y', strtotime($date_from)) . " s/d " . date('d/m/Y', strtotime($date_to));

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.report_export', compact('payments', 'title', 'total', 'periode'));
        return $pdf->download('Report-' . ($request->input('method') ?? 'All') . '-' . date('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = JobOrderPayment::query()->with(['jobOrder.customer', 'jobOrder.unit']);
        
        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }
        
        // Include filters
        if ($request->filled('customer_id')) {
            $query->whereHas('jobOrder', function($q) use ($request) {
                $q->where('customer_id', $request->input('customer_id'));
            });
        }
        
        $date_from = $request->input('date_from') ?: now()->startOfMonth()->format('Y-m-d');
        $date_to = $request->input('date_to') ?: now()->endOfMonth()->format('Y-m-d');
        
        $query->whereDate('payment_date', '>=', $date_from);
        $query->whereDate('payment_date', '<=', $date_to);

        $payments = $query->orderBy('payment_date', 'asc')->get();
        $total = $payments->sum('amount');
        $title = "LAPORAN TRANSAKSI " . strtoupper($request->input('method') ?? 'KESELURUHAN');
        $periode = date('d/m/Y', strtotime($date_from)) . " s/d " . date('d/m/Y', strtotime($date_to));
        $filename = "Laporan-" . ($request->input('method') ?? 'Keseluruhan') . "-" . date('Ymd-His') . ".xls";

        // Create HTML table for Excel kawan
        $view = view('payments.report_export', compact('payments', 'title', 'total', 'periode'))->render();
        
        return response($view)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', "attachment; filename=$filename")
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function settledReport(Request $request)
    {
        $query = JobOrder::query()->with(['customer', 'unit', 'driver', 'payments'])
                        ->where('payment_status', 'Lunas');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->whereDate('payment_date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->whereDate('payment_date', '<=', $request->date_to);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('spj_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $jobOrders = $query->latest('updated_at')->paginate(20)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        return view('payments.settled_report', compact('jobOrders', 'customers'));
    }

    public function settle(JobOrder $jobOrder)
    {
        $jobOrder->update(['payment_status' => 'Lunas']);
        return redirect()->back()->with('success', 'Data SPJ ' . $jobOrder->spj_number . ' telah dipindahkan ke Data Lunas kawan!');
    }

    public function unsettle(JobOrder $jobOrder)
    {
        $jobOrder->update(['payment_status' => 'DP']);
        return redirect()->back()->with('success', 'Status Lunas untuk SPJ ' . $jobOrder->spj_number . ' telah dibatalkan kawan!');
    }


    public function downloadPaymentReceipt(JobOrderPayment $payment)
    {
        $payment->load(['jobOrder.customer', 'jobOrder.unit', 'user']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.receipt_pdf', compact('payment'))
                 ->setPaper([0, 0, 226.77, 500], 'portrait'); // ~80mm width
        
        return $pdf->download('Receipt-PAY-' . $payment->id . '.pdf');
    }

    public function downloadClaimReceipt(JobOrderClaim $claim)
    {
        $claim->load(['jobOrder.customer', 'jobOrder.unit', 'user']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payments.claim_pdf', compact('claim'))
                 ->setPaper([0, 0, 226.77, 500], 'portrait'); // ~80mm width
        
        return $pdf->download('Receipt-CLAIM-' . $claim->id . '.pdf');
    }

    public function claims(Request $request)
    {
        $query = JobOrder::query()->with(['customer', 'unit', 'driver', 'claims', 'payments'])
                        ->where(function($q) {
                            $q->whereHas('claims')
                              ->orWhere('payment_status', '!=', 'Lunas');
                        });

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('date_from')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->whereDate('payment_date', '>=', $request->date_from);
            });
        }

        if ($request->filled('date_to')) {
            $query->whereHas('payments', function($q) use ($request) {
                $q->whereDate('payment_date', '<=', $request->date_to);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('spj_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $jobOrders = $query->latest('created_at')->paginate(20)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        return view('payments.claims', compact('jobOrders', 'customers'));
    }
}
