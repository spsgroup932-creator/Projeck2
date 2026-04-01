<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::with('branch')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar unit armada berhasil diambil.',
            'data' => $units
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = Unit::with(['branch', 'maintenanceLogs'])->find($id);

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Unit tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail unit berhasil diambil.',
            'data' => $unit
        ]);
    }
}
