<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the branches.
     */
    public function index()
    {
        // Mencatat jumlah user per rental dan status online kawan
        $branches = Branch::withCount(['users', 'admins', 'regularUsers', 'onlineUsers'])->get();
        return view('branches.index', compact('branches'));
    }

    /**
     * Store a newly created branch in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:branches|max:20',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'accessible_menus' => 'nullable|array',
        ]);

        Branch::create($request->all());

        return redirect()->back()->with('success', 'Rental baru berhasil didaftarkan kawan!');
    }

    /**
     * Update the specified branch in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:branches,code,' . $branch->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'header_color' => 'nullable|string|max:7',
            'accessible_menus' => 'nullable|array',
        ]);

        $branch->update($request->all());
        $branch->update(['accessible_menus' => $request->accessible_menus ?? []]);

        return redirect()->back()->with('success', 'Data rental berhasil diperbarui kawan!');
    }

    /**
     * Remove the specified branch from storage.
     */
    public function destroy(Branch $branch)
    {
        if ($branch->users()->count() > 0) {
            return redirect()->back()->with('error', 'Rental tidak bisa dihapus karena masih ada staff yang terdaftar kawan!');
        }

        $branch->delete();
        return redirect()->back()->with('success', 'Rental berhasil dihapus kawan!');
    }
}
