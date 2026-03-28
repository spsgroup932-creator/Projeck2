<x-app-layout>
    <x-slot name="header">Tambah Staff Baru</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        
                        <div class="section-title mb-5 pb-3 border-bottom border-white border-opacity-10">
                            <h4 class="fw-bold text-white mb-1 outfit">Registrasi Akun Staff</h4>
                            <p class="text-white opacity-50 small">Lengkapi data untuk mendaftarkan staff baru di sistem kawan.</p>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-12">
                                <label class="form-label text-white opacity-50">Nama Lengkap Staff</label>
                                <input type="text" name="name" class="form-control glass-panel @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Dani Wijaya">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-white opacity-50">Alamat Email Resmi</label>
                                <input type="email" name="email" class="form-control glass-panel @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@rental.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white opacity-50">Password Akses</label>
                                <input type="password" name="password" class="form-control glass-panel @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white opacity-50">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control glass-panel" placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            @if(strtolower(auth()->user()->role) === 'super admin')
                            <div class="col-md-12">
                                <h5 class="fw-bold text-white mb-3 outfit">Otoritas & Hak Akses</h5>
                                <label class="form-label text-white opacity-50">Tingkat Jabatan / Role</label>
                                <select name="role" id="roleSelect" class="form-select glass-panel @error('role') is-invalid @enderror">
                                    <option value="">Pilih Otoritas...</option>
                                    <option value="super admin" {{ old('role') == 'super admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin cabang" {{ old('role') == 'admin cabang' ? 'selected' : '' }}>Admin Rental</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Staff Lapangan</option>
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12" id="branchSection">
                                <label class="form-label text-white opacity-50">Penempatan Rental</label>
                                <select name="branch_id" class="form-select glass-panel @error('branch_id') is-invalid @enderror">
                                    <option value="">Pilih Rental kawan...</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }} (#{{ $branch->code }})</option>
                                    @endforeach
                                </select>
                                @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @else
                                <input type="hidden" name="role" value="user">
                            @endif

                            <div class="col-md-12">
                                <label class="form-label d-block mb-3 text-white opacity-50">Hak Akses Fitur (Menu Delegation)</label>
                                <div class="row g-3">
                                    @foreach($menus as $key => $menu)
                                    <div class="col-md-4">
                                        <div class="glass-panel p-3 transition hover-lift">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="accessible_menus[]" value="{{ $key }}" id="menu_{{ $key }}">
                                                <label class="form-check-label fw-semibold text-white" for="menu_{{ $key }}">
                                                    <i class="{{ $menu['icon'] ?? 'bi bi-command' }} me-2 text-primary"></i> {{ $menu['label'] }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-4 border-top border-white border-opacity-10">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-lg">
                                <i class="bi bi-person-check-fill me-2"></i> Buat Akun Staff
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
            if (roleSelect.value === 'super admin') {
                branchSection.style.display = 'none';
            } else {
                branchSection.style.display = 'block';
            }
        }

        roleSelect.addEventListener('change', toggleBranch);
        toggleBranch(); // Initial check
    </script>
</x-app-layout>
