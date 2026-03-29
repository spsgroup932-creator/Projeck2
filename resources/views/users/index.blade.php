<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-people-fill fs-4"></i>
            <span>Manajemen Staff & Akun</span>
            <span class="badge bg-danger rounded-pill ms-2">{{ $users->total() ?? $users->count() }}</span>
        </div>
    </x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3">
            <i class="bi bi-person-plus-fill fs-6"></i> 
            <span class="d-none d-sm-inline">Tambah Staff Baru</span>
        </a>
    </x-slot>

    <div class="card border-0 shadow-lg mt-4">
        <!-- Search & View Toggles -->
        <div class="card-header border-bottom border-white border-opacity-10 py-4 px-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group glass-panel overflow-hidden">
                        <span class="input-group-text bg-transparent border-0 px-3">
                            <i class="bi bi-search text-white opacity-25"></i>
                        </span>
                        <input type="text" id="searchUser" class="form-control border-0 bg-transparent text-white ps-0" placeholder="Cari nama staff atau email..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group glass-panel p-1" role="group">
                        <button type="button" class="btn btn-dark active px-3" id="viewTable">
                            <i class="bi bi-table"></i>
                        </button>
                        <button type="button" class="btn btn-dark px-3" id="viewGrid">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div id="tableView" class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr class="text-white opacity-50 uppercase fs-7 tracking-wider">
                        <th class="ps-4">Nama Pelanggan / Admin</th>
                        <th>Alamat Email</th>
                        <th>Rental Terdaftar</th>
                        @if($isSuperAdmin)
                        <th>Total Staff</th>
                        <th>Sandi</th>
                        @endif
                        <th>Otoritas</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse($users as $user)
                    <tr class="user-row">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="sidebar-logo" style="width: 42px; height: 42px; background: linear-gradient(135deg, var(--primary), var(--secondary)); shadow: none;">
                                    <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="fw-bold text-white">{{ $user->name }}</div>
                                    <div class="text-white opacity-50 small">ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="font-monospace text-white opacity-75">{{ $user->email }}</span></td>
                        <td>
                            @if($user->branch)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-building text-info opacity-75"></i>
                                    <span class="fw-medium text-white">{{ $user->branch->name }}</span>
                                </div>
                            @else
                                <span class="text-white opacity-50 small"><i>Semua Rental (Global)</i></span>
                            @endif
                        </td>
                        @if($isSuperAdmin)
                        <td class="text-center">
                            @if($user->branch && $user->role === 'admin cabang')
                                <button class="btn btn-sm btn-outline-info rounded-pill px-3 py-1" data-bs-toggle="modal" data-bs-target="#staffModal{{ $user->id }}">
                                    <i class="bi bi-people-fill me-1"></i> {{ $user->branch->users->count() }} Staff
                                </button>
                            @else
                                <span class="text-white opacity-25">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="font-monospace text-warning small">{{ $user->password_plain ?? 'N/A' }}</span>
                        </td>
                        @endif
                        <td>
                            @php
                                $roleColor = match(strtolower($user->role)) {
                                    'super admin' => 'danger',
                                    'admin cabang' => 'warning',
                                    'user' => 'info',
                                    default => 'secondary'
                                };
                                $roleLabel = match(strtolower($user->role)) {
                                    'super admin' => 'Super Admin',
                                    'admin cabang' => 'Admin Rental',
                                    'user' => 'Staff Lapangan',
                                    default => $user->role
                                };
                            @endphp
                            <span class="badge-pill bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} border border-{{ $roleColor }} border-opacity-20">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group glass-panel p-1">
                                @if($isSuperAdmin && $user->branch && $user->role === 'admin cabang')
                                <button class="btn btn-dark btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#staffModal{{ $user->id }}" title="Lihat Daftar Staff kawan">
                                    <i class="bi bi-eye text-info"></i>
                                </button>
                                @endif
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-dark btn-sm rounded-3 px-3" title="Edit">
                                    <i class="bi bi-pencil-square text-warning"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah kawan yakin?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3" title="Hapus">
                                        <i class="bi bi-trash3 text-danger"></i>
                                    </button>
                                </form>
                            </div>

                            @if($isSuperAdmin && $user->branch && $user->role === 'admin cabang')
                            <!-- Staff Popup Modal kawan -->
                            <div class="modal fade" id="staffModal{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content glass-panel border-0 shadow-lg text-start">
                                        <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                                            <div>
                                                <h6 class="modal-title fw-bold outfit text-white mb-0"><i class="bi bi-people-fill text-info me-2"></i>Daftar Staff: {{ $user->branch->name }}</h6>
                                                <small class="text-white opacity-50">Dikelola oleh Admin: {{ $user->name }} kawan.</small>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle mb-0">
                                                    <thead class="bg-white bg-opacity-5">
                                                        <tr class="text-white opacity-50 small text-uppercase">
                                                            <th class="ps-4">Nama Staff</th>
                                                            <th>Email</th>
                                                            <th class="text-end pe-4">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-white">
                                                        @forelse($user->branch->users as $staff)
                                                        <tr>
                                                            <td class="ps-4">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                                        {{ strtoupper(substr($staff->name, 0, 1)) }}
                                                                    </div>
                                                                    <span class="fw-bold">{{ $staff->name }}</span>
                                                                </div>
                                                            </td>
                                                            <td><span class="opacity-75 small">{{ $staff->email }}</span></td>
                                                            <td class="text-end pe-4">
                                                                <a href="{{ route('users.edit', $staff->id) }}" class="btn btn-sm btn-dark rounded-pill px-3">Detail</a>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center py-4 text-white opacity-25 italic">Belum ada staff terdaftar kawan.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top border-white border-opacity-10 p-4 pt-1">
                                            <button type="button" class="btn btn-outline-light px-4 border-0" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-white opacity-50">Belum ada staff terdaftar kawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="p-4" style="display: none;">
            <div class="row g-4" id="userGridBody">
                @foreach($users as $user)
                <div class="col-md-6 col-lg-4 col-xl-3 user-card">
                    <div class="card h-100 p-4 text-center">
                        <div class="sidebar-logo mx-auto mb-3" style="width: 64px; height: 64px; box-shadow: none;">
                            <span class="text-white fs-4 fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <h5 class="fw-bold mb-1 text-white">{{ $user->name }}</h5>
                        <div class="text-white opacity-50 small mb-3">{{ $user->email }}</div>
                        <div class="mb-4">
                            <span class="badge-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10">
                                {{ $user->branch?->name ?? 'Global Account' }}
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            @if($isSuperAdmin && $user->branch && $user->role === 'admin cabang')
                            <button class="btn btn-info btn-sm w-100 rounded-pill" data-bs-toggle="modal" data-bs-target="#staffModal{{ $user->id }}">Lihat Staff</button>
                            @endif
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-light btn-sm w-100 rounded-pill">Detail</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer border-top border-white border-opacity-10 bg-transparent py-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-white opacity-50 small">
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() ?? 0 }} staff kawan.
                </div>
                <div class="pagination-premium">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('searchUser')?.addEventListener('keyup', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.user-row').forEach(row => { row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none'; });
        document.querySelectorAll('.user-card').forEach(card => { card.style.display = card.innerText.toLowerCase().includes(term) ? '' : 'none'; });
    });
    const vt = document.getElementById('viewTable'), vg = document.getElementById('viewGrid'), tv = document.getElementById('tableView'), gv = document.getElementById('gridView');
    vt?.addEventListener('click', () => { tv.style.display = 'block'; gv.style.display = 'none'; vt.classList.add('active'); vg.classList.remove('active'); localStorage.setItem('userView', 'table'); });
    vg?.addEventListener('click', () => { tv.style.display = 'none'; gv.style.display = 'block'; vg.classList.add('active'); vt.classList.remove('active'); localStorage.setItem('userView', 'grid'); });
    if(localStorage.getItem('userView') === 'grid') vg?.click();
</script>
