<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount(['users', 'admins', 'regularUsers', 'onlineUsers'])->get();
        return view('branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:branches|max:20',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'accessible_menus' => 'nullable|array',
            'subscription_amount' => 'nullable|numeric|min:0',
            'subscription_due_at' => 'nullable|date',
        ]);

        $data = $request->all();
        if (isset($data['accessible_menus']) && is_array($data['accessible_menus'])) {
            // Count all selected features as 50k kawan
            $count = count($data['accessible_menus']);
            $data['subscription_amount'] = $count * 50000;
        }

        Branch::create($data);
        return redirect()->back()->with('success', 'Rental baru berhasil didaftarkan kawan!');
    }

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
            'subscription_amount' => 'nullable|numeric|min:0',
            'subscription_due_at' => 'nullable|date',
        ]);

        $data = $request->all();
        
        // Auto-calculate subscription_amount based on major menus kawan
        if (isset($data['accessible_menus']) && is_array($data['accessible_menus'])) {
            // Count all selected features as 50k kawan
            $count = count($data['accessible_menus']);
            // Let's enforce it to match user's wish for "otomatis".
            $data['subscription_amount'] = $count * 50000;
        }

        $branch->update($data);
        $branch->update(['accessible_menus' => $request->accessible_menus ?? []]);
        return redirect()->back()->with('success', 'Data rental berhasil diperbarui kawan!');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->users()->count() > 0) {
            return redirect()->back()->with('error', 'Rental tidak bisa dihapus karena masih ada staff yang terdaftar kawan!');
        }
        $branch->delete();
        return redirect()->back()->with('success', 'Rental berhasil dihapus kawan!');
    }

    public function settings()
    {
        $user = auth()->user();

        // Super Admin -> redirect ke System Settings
        if ($user->role === 'super admin') {
            return redirect()->route('settings.system');
        }

        $branch = $user->branch;
        if (!$branch) {
            return redirect()->route('dashboard')->with('error', 'User tidak memiliki akses ke pengaturan rental kawan!');
        }

        return view('settings.rental', compact('branch'));
    }

    public function updateSettings(Request $request)
    {
        $branch = auth()->user()->branch;
        if (!$branch) return abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'nib' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'receipt_footer' => 'nullable|string',
            'watermark_text' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'watermark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'header_color' => 'nullable|string|max:7',
            'font_family' => 'nullable|string|max:50',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except(['logo', 'watermark', 'qris_image']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('branch_logos', 'public');
        }
        if ($request->hasFile('watermark')) {
            $data['watermark'] = $request->file('watermark')->store('branch_watermarks', 'public');
        }
        if ($request->hasFile('qris_image')) {
            $data['qris_image'] = $request->file('qris_image')->store('branch_qris', 'public');
        }

        $branch->update($data);

        if ($request->has('font_size')) {
            auth()->user()->update(['font_size' => $request->font_size]);
        }

        return redirect()->back()->with('success', 'Pengaturan rental berhasil diperbarui kawan!');
    }

    public function systemSettings()
    {
        if (auth()->user()->role !== 'super admin') {
            return abort(403);
        }

        $branches = Branch::withCount(['admins', 'onlineUsers'])->get();
        $appName = SystemSetting::get('app_name', config('app.name'));
        $appVersion = SystemSetting::get('app_version', '1.0.0');
        $supportEmail = SystemSetting::get('support_email', '');

        return view('settings.system', compact('branches', 'appName', 'appVersion', 'supportEmail'));
    }

    public function updateSystemSettings(Request $request)
    {
        if (auth()->user()->role !== 'super admin') {
            return abort(403);
        }

        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_version' => 'nullable|string|max:20',
            'support_email' => 'nullable|email|max:255',
            'font_size' => 'nullable|string|max:10',
        ]);

        SystemSetting::set('app_name', $request->app_name);
        SystemSetting::set('app_version', $request->app_version);
        SystemSetting::set('support_email', $request->support_email);

        if ($request->has('font_size')) {
            auth()->user()->update(['font_size' => $request->font_size]);
        }

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui kawan!');
    }
}
