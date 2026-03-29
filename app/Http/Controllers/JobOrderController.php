<?php

namespace App\Http\Controllers;

use App\Models\JobOrder;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\Driver;
use App\Models\JobOrderPayment;
use App\Models\JobOrderClaim;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JobOrderController extends Controller
{
    public function index()
    {
        $jobOrders = JobOrder::with(['customer', 'unit', 'driver', 'user', 'checklists'])
            ->where('is_closed', false)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('job_orders.index', compact('jobOrders'));
    }

    public function closed()
    {
        $jobOrders = JobOrder::with(['customer', 'unit', 'driver', 'user'])
            ->where('is_closed', true)
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('job_orders.closed', compact('jobOrders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $drivers = Driver::orderBy('name')->get();
        
        $user = auth()->user();
        $branchCode = $user->branch ? $user->branch->code : 'GEN';
        $today = Carbon::now()->format('ymd'); // Use YYMMDD for shorter format
        $prefix = $branchCode . '-SPJ-' . $today . '-';
        
        $lastSpj = JobOrder::withoutGlobalScopes()
            ->where('spj_number', 'like', $prefix . '%')
            ->orderBy('spj_number', 'desc')
            ->first();
            
        if ($lastSpj) {
            $lastNumber = (int)substr($lastSpj->spj_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        
        $spjNumber = $prefix . $newNumber;

        return view('job_orders.create', compact('customers', 'units', 'drivers', 'spjNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'unit_id' => 'required|exists:units,id',
            'driver_id' => 'required|exists:drivers,id',
            'destination' => 'required|string',
            'departure_date' => 'required|date',
            'departure_time' => 'required',
            'return_date' => 'required|date|after_or_equal:departure_date',
            'price_per_day' => 'required|numeric',
            'days_count' => 'required|numeric|min:1',
            'total_price' => 'required|numeric',
            'payment_status' => 'required|in:Lunas,DP',
            'initial_payment' => 'nullable|numeric|min:0',
        ]);

        $user = auth()->user();
        $branchCode = $user->branch ? $user->branch->code : 'GEN';
        $today = Carbon::now()->format('ymd');
        $prefix = $branchCode . '-SPJ-' . $today . '-';

        // Hitung ulang di store agar tidak bentrok kawan
        $lastSpj = JobOrder::withoutGlobalScopes()
            ->where('spj_number', 'like', $prefix . '%')
            ->orderBy('spj_number', 'desc')
            ->first();

        if ($lastSpj) {
            $lastNumber = (int)substr($lastSpj->spj_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $data = $request->except('initial_payment');
        $data['spj_number'] = $prefix . $newNumber;
        $data['user_id'] = $user->id;

        $jobOrder = JobOrder::create($data);

        // Jika ada pembayaran awal kawan
        if ($request->initial_payment > 0) {
            $jobOrder->payments()->create([
                'amount' => $request->initial_payment,
                'method' => 'CASH', // Default cash
                'payment_date' => now(),
                'user_id' => $user->id,
                'branch_id' => $jobOrder->branch_id,
            ]);
        } elseif ($request->payment_status === 'Lunas') {
            // Jika lunas tapi tidak isi nominal, anggap bayar full kawan
            $jobOrder->payments()->create([
                'amount' => $jobOrder->total_price,
                'method' => 'CASH',
                'payment_date' => now(),
                'user_id' => $user->id,
                'branch_id' => $jobOrder->branch_id,
            ]);
        }
 
        return redirect()->route('job-orders.index')->with('success', 'Job Order berhasil dibuat kawan! Nomor SPJ: ' . $data['spj_number']);
    }

    public function show(JobOrder $jobOrder)
    {
        $jobOrder->load(['customer', 'unit', 'driver', 'user', 'payments', 'claims']);
        return view('job_orders.show', compact('jobOrder'));
    }

    public function destroy(JobOrder $jobOrder)
    {
        $jobOrder->delete();
        return redirect()->route('job-orders.index')->with('success', 'Job Order berhasil dihapus kawan!');
    }

    public function addPayment(Request $request, JobOrder $jobOrder)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
            'payment_date' => 'required|date',
        ]);

        $jobOrder->payments()->create([
            'amount' => $request->amount,
            'method' => $request->input('method'),
            'payment_date' => $request->payment_date,
            'user_id' => auth()->id(),
            'branch_id' => $jobOrder->branch_id,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan kawan!');
    }

    public function addClaim(Request $request, JobOrder $jobOrder)
    {
        $request->validate([
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $jobOrder->claims()->create([
            'description' => $request->description,
            'amount' => $request->amount,
            'user_id' => auth()->id(),
            'branch_id' => $jobOrder->branch_id,
        ]);

        return redirect()->back()->with('success', 'Claim kerusakan berhasil ditambahkan kawan!');
    }

    public function closeOrder(Request $request, JobOrder $jobOrder)
    {
        $request->validate([
            'closing_date' => 'required|date',
            'digital_signature' => 'required|string', // Base64 signature kawan
        ]);

        $prefix = 'CLS-' . $jobOrder->spj_number;
        
        $jobOrder->update([
            'closing_number' => $prefix,
            'closing_date' => $request->closing_date,
            'is_closed' => true,
            'digital_signature' => $request->digital_signature,
        ]);

        return redirect()->back()->with('success', 'Job Order berhasil ditutup (Closed) kawan!');
    }

    public function showClosed(JobOrder $jobOrder)
    {
        $jobOrder->load(['customer', 'unit', 'driver', 'user', 'payments', 'claims']);
        return view('job_orders.show_closed', compact('jobOrder'));
    }

    public function print(JobOrder $jobOrder)
    {
        $jobOrder->load(['customer', 'unit', 'driver', 'user']);
        return view('job_orders.print', compact('jobOrder'));
    }

    public function downloadPdf(JobOrder $jobOrder)
    {
        $jobOrder->load(['customer', 'unit', 'driver', 'user']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('job_orders.pdf', compact('jobOrder'))
                 ->setPaper('a4', 'portrait');
        
        return $pdf->download('SPJ-' . $jobOrder->spj_number . '.pdf');
    }

    public function receipt(JobOrder $jobOrder)
    {
        $jobOrder->load(['customer', 'unit', 'driver', 'user']);
        return view('job_orders.receipt', compact('jobOrder'));
    }

    public function report(Request $request)
    {
        $query = JobOrder::with(['customer', 'unit', 'driver', 'user', 'payments', 'claims'])
            ->where('is_closed', true);

        if ($request->filled('start_date')) {
            $query->whereDate('departure_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('departure_date', '<=', $request->end_date);
        }

        $jobOrders = $query->orderBy('departure_date', 'desc')->get();
        
        return view('job_orders.report', compact('jobOrders'));
    }

    public function export(Request $request)
    {
        $query = JobOrder::with(['customer', 'unit', 'driver', 'user', 'payments', 'claims'])
            ->where('is_closed', true);

        if ($request->filled('start_date')) {
            $query->whereDate('departure_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('departure_date', '<=', $request->end_date);
        }

        $jobOrders = $query->orderBy('departure_date', 'desc')->get();

        $fileName = "rekap-job-order-" . date('Ymd') . ".xls";

        $headers = [
            "Content-Type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use($jobOrders, $request) {
            $showStart = $request->start_date ?? '-';
            $showEnd = $request->end_date ?? '-';
            
            echo "
            <style>
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .bold { font-weight: bold; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #000; padding: 5px; }
                th { background-color: #f2f2f2; }
            </style>
            <table>
                <tr>
                    <th colspan='11' style='font-size: 16px; border: none;'>LAPORAN REKAPAN JOB ORDER</th>
                </tr>
                <tr>
                    <th colspan='11' style='border: none;'>Periode: $showStart s/d $showEnd</th>
                </tr>
                <tr><td colspan='11' style='border: none;'></td></tr>
                <thead>
                    <tr>
                        <th>No SPJ</th>
                        <th>No Closing</th>
                        <th>Customer</th>
                        <th>Unit</th>
                        <th>Driver</th>
                        <th>Berangkat</th>
                        <th>Kembali</th>
                        <th>Durasi</th>
                        <th>Tarif Total</th>
                        <th>Terbayar</th>
                        <th>Claim</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>";

            $totalTarif = 0;
            $totalBayar = 0;
            $totalClaim = 0;

            foreach ($jobOrders as $spj) {
                $claimSum = $spj->claims->sum('amount');
                echo "
                <tr>
                    <td>{$spj->spj_number}</td>
                    <td>{$spj->closing_number}</td>
                    <td>{$spj->customer->name}</td>
                    <td>{$spj->unit->nopol}</td>
                    <td>{$spj->driver->name}</td>
                    <td>{$spj->departure_date}</td>
                    <td>{$spj->return_date}</td>
                    <td class='text-center'>{$spj->days_count} hari</td>
                    <td class='text-right'>{$spj->total_price}</td>
                    <td class='text-right'>{$spj->paid_amount}</td>
                    <td class='text-right'>$claimSum</td>
                    <td class='text-center'>" . strtoupper($spj->payment_status) . "</td>
                </tr>";
                
                $totalTarif += $spj->total_price;
                $totalBayar += $spj->paid_amount;
                $totalClaim += $claimSum;
            }

            echo "
                <tr>
                    <td colspan='8' class='text-right bold'>TOTAL</td>
                    <td class='text-right bold'>$totalTarif</td>
                    <td class='text-right bold'>$totalBayar</td>
                    <td class='text-right bold'>$totalClaim</td>
                    <td></td>
                </tr>
                </tbody>
            </table>";
        };

        return response()->stream($callback, 200, $headers);
    }

    public function unclose(JobOrder $jobOrder)
    {
        $jobOrder->update([
            'is_closed' => false,
            'closing_number' => null,
            'closing_date' => null
        ]);

        return redirect()->route('job-orders.index')->with('success', 'Closing berhasil dibatalkan kawan! SPJ kembali ke daftar Open.');
    }
}
