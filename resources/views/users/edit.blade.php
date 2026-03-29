<x-app-layout>
    <x-slot name="header">Edit Staff: {{ $user->name }}</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="section-title mb-5 pb-3 border-bottom border-white border-opacity-10">
                            <h4 class="fw-bold text-white mb-1 outfit">Update Profil Staff</h4>
                            <p class="text-white opacity-50 small">Perbarui informasi akun dan otoritas untuk <strong>{{ $user->name }}</strong> kawan.</p>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-12">
                                <label class="form-label text-white opacity-50">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control glass-panel @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white opacity-50">Alamat Email</label>
                                <input type="email" name="email" class="form-control glass-panel @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white opacity-50">Ubah Password <small class="text-white opacity-25">(Opsional)</small></label>
                                <input type="password" name="password" class="form-control glass-panel @error('password') is-invalid @enderror" placeholder="Kosongkan jika tetap">
                                @if(strtolower(auth()->user()->role) === 'super admin')
                                    <div class="mt-1 small outfit text-warning">
                                        <i class="bi bi-key-fill me-1"></i> Sandi Saat Ini: <strong>{{ $user->password_plain ?? 'N/A' }}</strong>
                                    </div>
                                @endif
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            @if(strtolower(auth()->user()->role) === 'super admin')
                            <div class="col-md-6">
                                <label class="form-label text-white opacity-50">Tingkat Jabatan / Role</label>
                                <select name="role" id="roleSelect" class="form-select glass-panel @error('role') is-invalid @enderror">
                                    <option value="super admin" {{ old('role', $user->role) == 'super admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin cabang" {{ old('role', $user->role) == 'admin cabang' ? 'selected' : '' }}>Admin Rental</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Staff Lapangan</option>
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6" id="branchSection">
                                <label class="form-label text-white opacity-50">Penempatan Rental</label>
                                <select name="branch_id" class="form-select glass-panel @error('branch_id') is-invalid @enderror">
                                    <option value="">Pilih Rental kawan...</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }} (#{{ $branch->code }})</option>
                                    @endforeach
                                </select>
                                @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            <div class="col-md-12">
                                <label class="form-label d-block mb-3 fw-bold outfit text-white opacity-50">Delegasi Akses Menu</label>
                                <div class="row g-3">
                                    @php
                                        $userMenus = is_array($user->accessible_menus) ? $user->accessible_menus : [];
                                    @endphp
                                    @foreach($menus as $key => $menu)
                                    <div class="col-md-4">
                                        <div class="glass-panel p-3 transition hover-lift">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="accessible_menus[]" value="{{ $key }}" id="menu_{{ $key }}" 
                                                    {{ in_array($key, $userMenus) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold text-white" for="menu_{{ $key }}">
                                                    <i class="{{ $menu['icon'] ?? 'bi bi-command' }} me-2 text-primary"></i> {{ $menu['label'] }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @if(strtolower($user->role) === 'super admin')
                                <div class="glass-panel p-3 mt-3 border-danger border-opacity-20">
                                    <small class="text-danger fw-medium"><i class="bi bi-shield-lock-fill me-1"></i> Super Admin memiliki akses penuh ke semua fitur kawan.</small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-4 border-top border-white border-opacity-10">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-lg">
                                <i class="bi bi-save2-fill me-2"></i> Simpan Perubahan kawan!
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const roleSelect = document.getElementById('roleSelect');
        const branchSection = document.getElementById('branchSection');

        function toggleBranch() {
            if (roleSelect && branchSection) {
                branchSection.style.display = (roleSelect.value === 'super admin') ? 'none' : 'block';
            }
        }

        roleSelect?.addEventListener('change', toggleBranch);
        toggleBranch();
    </script>
</x-app-layout>
