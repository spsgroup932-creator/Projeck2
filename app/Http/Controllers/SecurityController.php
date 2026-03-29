<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'branch'])->latest();

        if ($request->filled('branch_id') && auth()->user()->role == 'super admin') {
            $query->where('branch_id', $request->branch_id);
        } elseif (auth()->user()->role != 'super admin') {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('model_type', 'like', "%$search%")
              ->orWhere('description', 'like', "%$search%");
        }

        $logs = $query->paginate(20);
        
        return view('security.index', compact('logs'));
    }

    public function show(ActivityLog $log)
    {
        // Ensure user can only see their branch log if not super admin
        if (auth()->user()->role != 'super admin' && $log->branch_id != auth()->user()->branch_id) {
            abort(403);
        }

        return view('security.show', compact('log'));
    }
}
