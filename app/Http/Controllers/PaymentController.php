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

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('jobOrder', function($q) use ($search) {
                $q->where('spj_number', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest('payment_date')->paginate(30)->withQueryString();
        $customers = Customer::orderBy('name')->get();
        $totalMoney = $query->sum('amount');

        return view('payments.transactions', compact('payments', 'customers', 'totalMoney'));
    }

    public function settledReport(Request $request)
    {
        $query = JobOrder::query()->with(['customer', 'unit', 'driver'])
                        ->where('payment_status', 'Lunas');

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
        $query = JobOrder::query()->with(['customer', 'unit', 'driver', 'claims'])
                        ->where(function($q) {
                            $q->whereHas('claims')
                              ->orWhere('payment_status', '!=', 'Lunas');
                        });

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

        return view('payment.claims', compact('jobOrders', 'customers'));
    }
}
