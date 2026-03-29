<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-circle fs-4 text-primary"></i>
            <span class="outfit fw-bold">Pengaturan Profil Pengguna</span>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-10 border-success border-opacity-25 text-success rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-xl-6">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="card border-0 shadow-lg overflow-hidden">
                        <div class="card-header bg-primary py-4 px-4 text-white">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-white text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold outfit">{{ $user->name }}</h5>
                                    <span class="opacity-75 small text-uppercase fw-bold">{{ $user->role }} - {{ optional($user->branch)->name ?? 'System Admin' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4 p-md-5">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Alamat Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <hr class="my-2 border-secondary border-opacity-10">
                                
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Ukuran Font Dashboard (Khusus Anda)</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <select name="font_size" class="form-select bg-dark border-secondary text-white">
                                            <option value="12px" {{ auth()->user()->font_size == '12px' ? 'selected' : '' }}>Kecil (12px)</option>
                                            <option value="14px" {{ auth()->user()->font_size == '14px' ? 'selected' : '' }}>Sedang (14px)</option>
                                            <option value="16px" {{ (auth()->user()->font_size == '16px' || !auth()->user()->font_size) ? 'selected' : '' }}>Standar (16px)</option>
                                            <option value="18px" {{ auth()->user()->font_size == '18px' ? 'selected' : '' }}>Besar (18px)</option>
                                            <option value="20px" {{ auth()->user()->font_size == '20px' ? 'selected' : '' }}>Sangat Besar (20px)</option>
                                        </select>
                                        <span class="badge bg-primary bg-opacity-10 text-primary p-2">Aa</span>
                                    </div>
                                    <small class="text-muted mt-2 d-block">Pengaturan ini hanya berpengaruh pada tampilan di akun kawan sendiri.</small>
                                </div>

                                <hr class="my-2 border-secondary border-opacity-10">

                                <div class="col-12">
                                    <div class="bg-warning bg-opacity-10 border border-warning border-opacity-25 p-3 rounded-3 mb-3">
                                        <div class="text-warning small fw-bold mb-1"><i class="bi bi-shield-lock me-1"></i> Ganti Password</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Kosongkan jika tidak ingin mengubah password kawan.</div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small fw-bold text-uppercase">Password Baru</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small fw-bold text-uppercase">Konfirmasi Password</label>
                                            <input type="password" name="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-4 pt-0">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow">
                                <i class="bi bi-save2 me-2"></i> SIMPAN PERUBAHAN PROFIL
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
