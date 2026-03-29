<x-app-layout>
    <x-slot name="header">
        <div>
            <h5 class="fw-bold mb-0 outfit"><i class="bi bi-gear-wide-connected text-primary me-2"></i>Pengaturan Sistem</h5>
            <small class="text-secondary small mb-0">Kelola konfigurasi aplikasi & langganan rental kawan.</small>
        </div>
    </x-slot>

    <div class="container-fluid py-4 font-sans text-white">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-10 border-success border-opacity-25 text-success rounded-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- KOLOM KIRI: Pengaturan Aplikasi & Profil --}}
            <div class="col-lg-5">

                {{-- Card: Pengaturan Aplikasi --}}
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4">
                        <h6 class="mb-0 fw-bold outfit text-white"><i class="bi bi-app-indicator text-primary me-2"></i>Konfigurasi Aplikasi</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('settings.system.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Nama Aplikasi</label>
                                <input type="text" name="app_name" class="form-control" value="{{ $appName }}" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Versi</label>
                                    <input type="text" name="app_version" class="form-control" value="{{ $appVersion }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Email Support</label>
                                    <input type="email" name="support_email" class="form-control" value="{{ $supportEmail }}">
                                </div>
                            </div>

                            <hr class="border-secondary opacity-10 my-4">

                            <div class="mb-3">
                                <label class="form-label text-warning small fw-bold text-uppercase"><i class="bi bi-person-gear"></i> Ukuran Font Dashboard (Khusus Anda)</label>
                                <select name="font_size" class="form-select bg-dark border-secondary text-white">
                                    <option value="12px" {{ auth()->user()->font_size == '12px' ? 'selected' : '' }}>Kecil (12px)</option>
                                    <option value="14px" {{ auth()->user()->font_size == '14px' ? 'selected' : '' }}>Sedang (14px)</option>
                                    <option value="16px" {{ (auth()->user()->font_size == '16px' || !auth()->user()->font_size) ? 'selected' : '' }}>Standar (16px)</option>
                                    <option value="18px" {{ auth()->user()->font_size == '18px' ? 'selected' : '' }}>Besar (18px)</option>
                                    <option value="20px" {{ auth()->user()->font_size == '20px' ? 'selected' : '' }}>Sangat Besar (20px)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Card: Data Diri Super Admin --}}
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4">
                        <h6 class="mb-0 fw-bold outfit text-white"><i class="bi bi-person-badge text-info me-2"></i>Data Diri Super Admin</h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Password Baru <span class="text-muted fw-normal">(kosongkan jika tidak diubah)</span></label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••">
                            </div>
                            <button type="submit" class="btn btn-info w-100 py-2 fw-bold shadow-sm text-white">
                                <i class="bi bi-pencil-square me-1"></i> Update Profil
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Daftar Rental & Billing --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-transparent border-bottom border-white border-opacity-10 p-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold outfit text-white"><i class="bi bi-buildings text-warning me-2"></i>Manajemen Langganan Rental</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">{{ $branches->count() }} Rental</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                        <th class="ps-4">Rental</th>
                                        <th>Admin</th>
                                        <th>Tagihan</th>
                                        <th>Jatuh Tempo</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-white">
                                    @forelse($branches as $branch)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="sidebar-logo" style="width: 38px; height: 38px; background: {{ $branch->header_color ?? 'var(--primary)' }}; display:flex; align-items:center; justify-content:center; border-radius:10px;">
                                                    <i class="bi bi-building text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-white small">{{ $branch->name }}</div>
                                                    <div class="text-white opacity-50" style="font-size:0.7rem;">#{{ $branch->code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info small">{{ $branch->admins_count }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary small">Rp {{ number_format($branch->subscription_amount ?? 0, 0, ',', '.') }}</span>
                                            @if($branch->subscription_amount > 0)
                                                <div class="text-white opacity-25" style="font-size: 0.6rem;">Otomatis Fitur</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($branch->subscription_due_at)
                                                @php $due = \Carbon\Carbon::parse($branch->subscription_due_at); @endphp
                                                @if($due->isPast())
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 small"><i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $due->format('d M Y') }}</span>
                                                @elseif($due->diffInDays(now()) <= 7)
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 small"><i class="bi bi-clock-fill me-1"></i>{{ $due->format('d M Y') }}</span>
                                                @else
                                                    <span class="text-white opacity-75 small">{{ $due->format('d M Y') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">Belum diset</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($branch->is_active)
                                                <span class="badge bg-success bg-opacity-10 text-success small">Aktif</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger small">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-sm btn-outline-warning border-0 px-2" data-bs-toggle="modal" data-bs-target="#billingModal{{ $branch->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Edit Billing --}}
                                    <div class="modal fade" id="billingModal{{ $branch->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content glass-panel border-0 shadow-lg">
                                                <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                                                    <h6 class="modal-title fw-bold outfit text-white"><i class="bi bi-receipt-cutoff text-warning me-2"></i>Tagihan: {{ $branch->name }}</h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="name" value="{{ $branch->name }}">
                                                    <input type="hidden" name="code" value="{{ $branch->code }}">
                                                    <div class="modal-body p-4 text-white">
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold text-uppercase opacity-75">Jumlah Tagihan (Rp)</label>
                                                            <input type="number" name="subscription_amount" class="form-control" value="{{ $branch->subscription_amount }}" placeholder="Contoh: 500000" step="1000">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small fw-bold text-uppercase opacity-75">Tanggal Jatuh Tempo</label>
                                                            <input type="date" name="subscription_due_at" class="form-control" value="{{ $branch->subscription_due_at }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top border-white border-opacity-10 p-4 pt-0">
                                                        <button type="button" class="btn btn-outline-light px-4 border-0" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-warning px-4 fw-bold text-dark">Simpan Tagihan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                                            Belum ada rental terdaftar kawan.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
