<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use App\Models\Branch;

class UserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = strtolower($user->role) === 'super admin';

        $query = User::with(['branch.users' => function($q) {
            $q->where('role', 'user'); // Load staff only
        }]);

        if ($isSuperAdmin) {
            // Super Admin melihat semua Admin Cabang and Super Admin kawan
            $query->whereIn('role', ['super admin', 'admin cabang']);
        } else {
            // Admin Cabang sudah difilter oleh Global Scope BelongsToBranch kawan
            // tapi kita pastikan tetap memuat relasi kalau perlu kawan.
        }

        $users = $query->latest()->paginate(10);
        return view('users.index', compact('users', 'isSuperAdmin'));
    }

    public function create()
    {
        $user = auth()->user();
        $allMenus = config('menus');
        $menus = $allMenus;

        // Filter menu berdasarkan role kawan
        foreach ($menus as $key => $menu) {
            // JANGAN tampilkan Master Rental dan Manajemen User di pilihan hak akses kawan
            if (in_array($key, ['branches', 'users'])) {
                unset($menus[$key]);
                continue;
            }

            // Jika menu punya syarat role, cek kawan
            if (isset($menu['role'])) {
                if (strtolower($user->role) !== strtolower($menu['role'])) {
                    unset($menus[$key]);
                    continue;
                }
            }
        }

        // Jika bukan super admin, filter lagi berdasarkan menu yang diizinkan untuk Cabang ini kawan
        if (strtolower($user->role) !== 'super admin' && $user->branch) {
            $branchAllowed = is_array($user->branch->accessible_menus) ? $user->branch->accessible_menus : [];
            $menus = array_intersect_key($menus, array_flip($branchAllowed));
            
            // Filter lagi berdasarkan menu yang HANYA dimiliki user ini kawan (Sub-delegasi)
            $myMenus = is_array($user->accessible_menus) ? $user->accessible_menus : [];
            $menus = array_intersect_key($menus, array_flip($myMenus));
        }

        $branches = Branch::all();
        return view('users.create', compact('menus', 'branches'));
    }

    public function store(Request $request)
    {
        $currentUser = auth()->user();
        $isSuperAdmin = strtolower($currentUser->role) === 'super admin';

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'accessible_menus' => 'nullable|array',
        ];

        if ($isSuperAdmin) {
            $rules['role'] = ['required', Rule::in(['super admin', 'admin cabang', 'user'])];
            $rules['branch_id'] = 'required_unless:role,super admin|nullable|exists:branches,id';
        } else {
            // Admin hanya bisa buat 'user' (Staff Lapangan) kawan
            $rules['role'] = ['required', Rule::in(['user'])];
        }

        $validated = $request->validate($rules);

        $validated['password_plain'] = $validated['password']; // Simpan teks aslinya kawan
        $validated['password'] = Hash::make($validated['password']);
        $validated['accessible_menus'] = $request->accessible_menus ?? [];
        
        // Otomatis ikut rental admin kawan
        if (!$isSuperAdmin) {
            $validated['branch_id'] = $currentUser->branch_id;
            $validated['role'] = 'user'; // Paksa jadi user staff kawan
        }

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Staff baru berhasil terdaftar di rental kawan!');
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();
        $allMenus = config('menus');
        
        // Admin hanya bisa edit staff di rental yang sama kawan
        if (strtolower($currentUser->role) !== 'super admin') {
            if ($user->branch_id !== $currentUser->branch_id) {
                abort(403, 'Akses ditolak kawan!');
            }
        }

        $menus = $allMenus;
        
        // Filter menu berdasarkan role kawan
        foreach ($menus as $key => $menu) {
            // Explicitly sembunyikan menu manajemen dari daftar pilihan kawan
            if (in_array($key, ['branches', 'users'])) {
                unset($menus[$key]);
                continue;
            }

            if (isset($menu['role'])) {
                if (strtolower($currentUser->role) !== strtolower($menu['role'])) {
                    unset($menus[$key]);
                    continue;
                }
            }
        }

        // Filter lagi berdasarkan menu milik Cabang kawan
        if (strtolower($currentUser->role) !== 'super admin' && $currentUser->branch) {
            $branchAllowed = is_array($currentUser->branch->accessible_menus) ? $currentUser->branch->accessible_menus : [];
            $menus = array_intersect_key($menus, array_flip($branchAllowed));

            // Filter lagi berdasarkan menu milik current user kawan (Sub-delegasi)
            $myMenus = is_array($currentUser->accessible_menus) ? $currentUser->accessible_menus : [];
            $menus = array_intersect_key($menus, array_flip($myMenus));
        }

        $branches = Branch::all();
        return view('users.edit', compact('user', 'menus', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();
        $isSuperAdmin = strtolower($currentUser->role) === 'super admin';

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'accessible_menus' => 'nullable|array',
        ];

        if ($isSuperAdmin) {
            $rules['role'] = ['required', Rule::in(['super admin', 'admin cabang', 'user'])];
            $rules['branch_id'] = 'required_unless:role,super admin|nullable|exists:branches,id';
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $request->validate(['password' => 'required|string|min:8|confirmed']);
            $validated['password_plain'] = $request->password; // Update teks asli kawan
            $validated['password'] = Hash::make($request->password);
        }

        $validated['accessible_menus'] = $request->accessible_menus ?? [];

        // Proteksi branch_id kawan
        if (!$isSuperAdmin) {
            unset($validated['branch_id']);
            unset($validated['role']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Data staff berhasil diperbarui kawan!');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete yourself.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('users.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'font_size' => 'nullable|string|max:10',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'required|string|min:8|confirmed']);
            $user->password_plain = $request->password; // Update teks asli kawan
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->font_size = $request->font_size ?? '16px';
        $user->save();

        return redirect()->back()->with('success', 'Profil kawan berhasil diperbarui!');
    }
}
