<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('id', 'desc')->paginate(10);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nopol' => 'required|unique:units',
            'name' => 'required',
            'chassis_number' => 'required',
            'year' => 'required|numeric',
            'stnk_expiry' => 'nullable|date',
            'kir_expiry' => 'nullable|date',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents', 'public');
            $validated['document_path'] = $path;
        }
        unset($validated['document']);

        $last = Unit::latest('id')->first();
        $number = $last ? (int)substr($last->unit_code, 5) + 1 : 1;
        $code = 'UNIT-' . str_pad($number, 4, '0', STR_PAD_LEFT);

        Unit::create(array_merge($validated, [
            'unit_code' => $code,
            'branch_id' => auth()->user()->branch_id
        ]));
        return redirect()->route('units.index')->with('success', 'Unit berhasil ditambahkan kawan!');
    }

    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'nopol' => 'required|unique:units,nopol,' . $unit->id,
            'name' => 'required',
            'chassis_number' => 'required',
            'year' => 'required|numeric',
            'stnk_expiry' => 'nullable|date',
            'kir_expiry' => 'nullable|date',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('document')) {
            // Delete old document if exists
            if ($unit->document_path && Storage::disk('public')->exists($unit->document_path)) {
                Storage::disk('public')->delete($unit->document_path);
            }
            $path = $request->file('document')->store('documents', 'public');
            $validated['document_path'] = $path;
        }
        unset($validated['document']);

        $unit->update($validated);
        return redirect()->route('units.index')->with('success', 'Unit berhasil diupdate kawan!');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit berhasil dihapus kawan!');
    }
}
