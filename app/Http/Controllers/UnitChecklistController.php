<?php

namespace App\Http\Controllers;

use App\Models\UnitChecklist;
use App\Models\JobOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UnitChecklistController extends Controller
{
    public function history(Request $request)
    {
        $query = UnitChecklist::with(['jobOrder.unit', 'jobOrder.driver', 'checker'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('jobOrder', function($q) use ($search) {
                $q->where('spj_number', 'like', "%$search%")
                  ->orWhereHas('unit', function($qu) use ($search) {
                      $qu->where('nopol', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                  });
            });
        }

        $checklists = $query->paginate(20);
        return view('job_orders.checklist.history', compact('checklists'));
    }

    public function index(Request $request)
    {
        // Query JobOrder yang belum lengkap checklistnya (Berangkat & Kembali)
        $query = JobOrder::with(['unit', 'customer', 'checklists', 'driver'])
            ->where('is_closed', false)
            ->where(function($q) {
                // Belum ada checklist sama sekali
                $q->whereDoesntHave('checklists')
                  // ATAU sudah ada checklist tapi belum ada tipe 'return'
                  ->orWhereDoesntHave('checklists', function($sq) {
                      $sq->where('type', 'return');
                  });
            })
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('spj_number', 'like', "%$search%")
                  ->orWhereHas('unit', function($qu) use ($search) {
                      $qu->where('nopol', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                  });
            });
        }

        $jobOrders = $query->paginate(15);
        return view('job_orders.checklist.index', compact('jobOrders'));
    }

    public function create(Request $request)
    {
        $jobOrder = JobOrder::findOrFail($request->job_order_id);
        $type = $request->type ?? 'departure'; // Default to departure right after JO creation
        
        // Prevent duplicate departure checklist
        if ($type === 'departure' && UnitChecklist::where('job_order_id', $jobOrder->id)->where('type', 'departure')->exists()) {
            return redirect()->route('job-orders.show', $jobOrder->id)->with('error', 'Checklist keberangkatan sudah diisi kawan!');
        }

        return view('job_orders.checklist.create', compact('jobOrder', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_order_id' => 'required|exists:job_orders,id',
            'type' => 'required|in:departure,return',
            'km_reading' => 'required|numeric',
            'fuel_level' => 'required',
            'check_date' => 'required|date',
        ]);

        $data = $request->all();
        $data['checker_id'] = auth()->id();
        
        // Items & Documents are submitted as arrays from checkboxes
        $data['items'] = $request->items ?? [];
        $data['documents'] = $request->documents ?? [];

        // Simple photo handling if needed, but for now we focus on data
        if ($request->hasFile('photo_files')) {
            $photos = [];
            foreach ($request->file('photo_files') as $file) {
                $photos[] = $file->store('checklist_photos', 'public');
            }
            $data['photos'] = $photos;
        }

        UnitChecklist::create($data);

        $msg = $request->type === 'departure' ? 'Unit berhasil Check-out! Hati-hati di jalan kawan.' : 'Unit berhasil Check-in! Terima kasih kawan.';
        
        return redirect()->route('job-orders.show', $request->job_order_id)->with('success', $msg);
    }
}
