<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitMaintenanceLog;
use Illuminate\Http\Request;

class UnitMaintenanceController extends Controller
{
    public function index()
    {
        $logs = UnitMaintenanceLog::with('unit')->latest()->paginate(15);
        return view('maintenance.index', compact('logs'));
    }

    public function create()
    {
        $units = Unit::all();
        return view('maintenance.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'service_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'mechanic_name' => 'nullable|string',
            'current_mileage' => 'nullable|integer',
        ]);

        $validated['branch_id'] = auth()->user()->branch_id;

        UnitMaintenanceLog::create($validated);

        return redirect()->route('maintenance.index')->with('success', 'Catatan maintenance berhasil ditambahkan kawan!');
    }

    public function edit(UnitMaintenanceLog $maintenance)
    {
        $units = Unit::all();
        return view('maintenance.edit', compact('maintenance', 'units'));
    }

    public function update(Request $request, UnitMaintenanceLog $maintenance)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'service_date' => 'required|date',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'mechanic_name' => 'nullable|string',
            'current_mileage' => 'nullable|integer',
        ]);

        $maintenance->update($validated);

        return redirect()->route('maintenance.index')->with('success', 'Catatan maintenance berhasil diperbarui kawan!');
    }

    public function destroy(UnitMaintenanceLog $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenance.index')->with('success', 'Catatan maintenance berhasil dihapus kawan!');
    }
}
