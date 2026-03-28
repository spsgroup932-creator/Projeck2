<x-app-layout>
    <x-slot name="header">
        <div>
            <h5 class="fw-bold mb-0 outfit"><i class="bi bi-building-gear text-primary me-2"></i>Master Rental</h5>
            <small class="text-secondary small mb-0">Kelola profil usaha dan pantau aktivitas staff per rental kawan.</small>
        </div>
    </x-slot>
    <x-slot name="header_actions">
        <div class="d-flex align-items-center gap-3">
            <div class="btn-group glass-panel p-1" role="group">
                <button type="button" class="btn btn-sm btn-dark active px-3" id="viewGrid">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </button>
                <button type="button" class="btn btn-sm btn-dark px-3" id="viewTable">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
            <button class="btn btn-sm btn-primary px-3 py-2 fw-bold shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                <i class="bi bi-plus-lg me-1"></i> Daftarkan Rental Baru
            </button>
        </div>
    </x-slot>

    <div class="container-fluid py-4 font-sans text-white">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-10 border-success border-opacity-25 text-success rounded-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger rounded-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Grid View -->
        <div id="gridView" class="row g-4">
            @foreach ($branches as $branch)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 hover-lift transition border-white border-opacity-10">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="position-relative">
                                <div class="sidebar-logo" style="width: 54px; height: 54px; background: {{ $branch->header_color ?? 'linear-gradient(135deg, var(--primary), var(--secondary))' }};">
                                    <i class="bi bi-building fs-3 text-white"></i>
                                </div>
                                @if($branch->online_users_count > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success border border-dark border-2" style="width: 14px; height: 14px; padding: 0;">
                                    <span class="visually-hidden">Online</span>
                                </span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($branch->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-1 small">Aktif</span>
                                @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1 small">Non-Aktif</span>
                                @endif
                                <div class="dropdown">
                                    <button class="btn btn-link text-white opacity-50 p-0" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow border-white border-opacity-10">
                                        <li><button class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $branch->id }}"><i class="bi bi-pencil-square me-2 text-warning"></i> Edit Rental</button></li>
                                        <li><hr class="dropdown-divider opacity-10"></li>
                                        <li>
                                            <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Apakah kawan yakin ingin menghapus rental ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-trash3 me-2"></i> Hapus Permanen</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <h4 class="fw-bold mb-1 outfit text-white">{{ $branch->name }}</h4>
                        <div class="mb-3 d-flex align-items-center gap-2">
                            <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 px-2 py-1 small">#{{ $branch->code }}</span>
                            @if($branch->online_users_count > 0)
                                <span class="text-success small fw-bold d-flex align-items-center gap-1">
                                    <i class="bi bi-person-check-fill"></i> {{ $branch->online_users_count }} Online kawan
                                </span>
                            @endif
                        </div>

                        <p class="text-white opacity-75 small mb-4 line-clamp-2" style="height: 40px;">
                            {{ $branch->description ?? 'Tidak ada deskripsi rental kawan.' }}
                        </p>

                        <div class="glass-panel p-3 mb-4 border-white border-opacity-10">
                            <div class="row g-0">
                                <div class="col-6 text-center border-end border-white border-opacity-10">
                                    <div class="h4 fw-bold mb-0 outfit text-white">{{ $branch->admins_count }}</div>
                                    <div class="text-white opacity-50" style="font-size: 0.65rem; letter-spacing: 1px; text-transform: uppercase;">Admin</div>
                                </div>
                                <div class="col-6 text-center">
                                    <div class="h4 fw-bold mb-0 outfit text-white">{{ $branch->regular_users_count }}</div>
                                    <div class="text-white opacity-50" style="font-size: 0.65rem; letter-spacing: 1px; text-transform: uppercase;">Staff</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2 text-white small">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-telephone-fill text-primary"></i> <span class="opacity-75">{{ $branch->phone ?? 'Belum ada kontak' }}</span>
                            </div>
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-geo-alt-fill text-primary"></i> 
                                <span class="line-clamp-2 opacity-75">{{ $branch->address ?? 'Alamat belum diset kawan.' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal Redesign -->
            <div class="modal fade" id="editModal{{ $branch->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content glass-panel border-0 shadow-lg">
                        <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                            <h5 class="modal-title fw-bold outfit text-white"><i class="bi bi-pencil-square me-2 text-warning"></i>Konfigurasi Rental</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-body p-4 text-white">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label opacity-75 small uppercase fw-bold">Nama Bisnis / Rental</label>
                                        <input type="text" name="name" class="form-control" value="{{ $branch->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label opacity-75 small uppercase fw-bold">Kode Unik</label>
                                        <input type="text" name="code" class="form-control" value="{{ $branch->code }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label opacity-75 small uppercase fw-bold">Warna Branding</label>
                                        <input type="color" name="header_color" class="form-control form-control-color w-100" value="{{ $branch->header_color }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label opacity-75 small uppercase fw-bold">Nomor Telepon</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $branch->phone }}" placeholder="Contoh: 0812...">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label opacity-75 small uppercase fw-bold">Slogan (Singkat)</label>
                                        <input type="text" name="description" class="form-control" value="{{ $branch->description }}" placeholder="Slogan marketing kawan...">
                                    </div>
                                    <div class="col-12 mt-4">
                                        <label class="form-label d-block mb-3 fw-bold outfit text-white opacity-50">Delegasi Fitur & Dashboard</label>
                                        <div class="row g-3">
                                            @php
                                                $allMenus = config('menus', []);
                                                $branchMenus = is_array($branch->accessible_menus) ? $branch->accessible_menus : [];
                                                $dashComponents = [
                                                    'dash_financial_chart' => ['label' => 'Grafik Finansial', 'icon' => 'bi bi-graph-up-arrow'],
                                                    'dash_utilization_chart' => ['label' => 'Grafik Utilitas', 'icon' => 'bi bi-pie-chart-fill'],
                                                    'dash_top_customers' => ['label' => 'Tabel Pelanggan Teraktif', 'icon' => 'bi bi-star-fill'],
                                                ];
                                            @endphp
                                            
                                            <!-- Menus -->
                                            @foreach($allMenus as $key => $menu)
                                                @if(!in_array($key, ['branches', 'users']))
                                                <div class="col-md-6">
                                                    <div class="glass-panel p-2">
                                                        <div class="form-check form-switch mb-0">
                                                            <input class="form-check-input" type="checkbox" name="accessible_menus[]" value="{{ $key }}" id="edit_menu_{{ $branch->id }}_{{ $key }}" 
                                                                {{ in_array($key, $branchMenus) ? 'checked' : '' }}>
                                                            <label class="form-check-label text-white small" for="edit_menu_{{ $branch->id }}_{{ $key }}">
                                                                <i class="{{ $menu['icon'] ?? 'bi bi-command' }} me-2 text-primary"></i> {{ $menu['label'] }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach

                                            <div class="col-12"><hr class="opacity-10 my-1"></div>

                                            <!-- Dashboard Components -->
                                            @foreach($dashComponents as $key => $comp)
                                                <div class="col-md-6">
                                                    <div class="glass-panel p-2 border-primary border-opacity-10">
                                                        <div class="form-check form-switch mb-0">
                                                            <input class="form-check-input" type="checkbox" name="accessible_menus[]" value="{{ $key }}" id="edit_dash_{{ $branch->id }}_{{ $key }}" 
                                                                {{ in_array($key, $branchMenus) ? 'checked' : '' }}>
                                                            <label class="form-check-label text-white small" for="edit_dash_{{ $branch->id }}_{{ $key }}">
                                                                <i class="{{ $comp['icon'] }} me-2 text-warning"></i> {{ $comp['label'] }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top border-white border-opacity-10 p-4 pt-0">
                                <button type="button" class="btn btn-outline-light px-4 border-0" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary px-5">Perbarui Rental kawan!</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Table View -->
        <div id="tableView" class="card border-0 shadow-lg" style="display: none;">
            <div class="table-container">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Cabang Rental</th>
                            <th>Kode</th>
                            <th>Online Status</th>
                            <th class="text-center">Total Staff</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach ($branches as $branch)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="sidebar-logo" style="width: 42px; height: 42px; background: {{ $branch->header_color ?? 'var(--primary)' }};">
                                        <i class="bi bi-building fs-5 text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white">{{ $branch->name }}</div>
                                        <div class="text-white opacity-50 small">{{ $branch->phone ?? 'No Phone' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-white bg-opacity-10 text-white-50">#{{ $branch->code }}</span></td>
                            <td>
                                @if($branch->online_users_count > 0)
                                    <span class="text-success small fw-bold"><i class="bi bi-circle-fill fs-xs me-1 small"></i> {{ $branch->online_users_count }} Online</span>
                                @else
                                    <span class="text-white opacity-25 small">Offline</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 small">{{ $branch->admins_count }} Admin</span>
                                    <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-10 small">{{ $branch->regular_users_count }} Staff</span>
                                </div>
                            </td>
                            <td>
                                @if($branch->is_active)
                                <span class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                                @else
                                <span class="text-danger small fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Tutup</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group glass-panel p-1">
                                    <button class="btn btn-dark btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#editModal{{ $branch->id }}">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </button>
                                    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus rental ini kawan?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal Redesign -->
    <div class="modal fade" id="addBranchModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-panel border-0 shadow-lg">
                <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                    <h5 class="modal-title fw-bold outfit text-white"><i class="bi bi-building-add me-2 text-primary"></i>Registrasi Rental Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('branches.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 text-white">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label opacity-75 small uppercase fw-bold">Nama Bisnis / Rental</label>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Arul Travel kawan" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label opacity-75 small uppercase fw-bold">Kode Unik (Singkat)</label>
                                <input type="text" name="code" class="form-control" placeholder="Contoh: AT-01" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label opacity-75 small uppercase fw-bold">Warna Branding</label>
                                <input type="color" name="header_color" class="form-control form-control-color w-100" value="#6366f1">
                            </div>
                            <div class="col-12">
                                <label class="form-label opacity-75 small uppercase fw-bold">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control" placeholder="Misal: 0812xxxx">
                            </div>
                            <div class="col-12">
                                <label class="form-label opacity-75 small uppercase fw-bold">Slogan / Deskripsi</label>
                                <input type="text" name="description" class="form-control" placeholder="Ceritakan dikit tentang rental ini kawan...">
                            </div>
                            <div class="col-12">
                                <label class="form-label opacity-75 small uppercase fw-bold">Alamat Kantor Pusat</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap kawan..."></textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <label class="form-label d-block mb-3 fw-bold outfit text-white opacity-50">Delegasi Fitur & Dashboard</label>
                                <div class="row g-3">
                                    @php
                                        $allMenus = config('menus', []);
                                        $dashComponents = [
                                            'dash_financial_chart' => ['label' => 'Grafik Finansial', 'icon' => 'bi bi-graph-up-arrow'],
                                            'dash_utilization_chart' => ['label' => 'Grafik Utilitas', 'icon' => 'bi bi-pie-chart-fill'],
                                            'dash_top_customers' => ['label' => 'Tabel Pelanggan Teraktif', 'icon' => 'bi bi-star-fill'],
                                        ];
                                    @endphp
                                    
                                    @foreach($allMenus as $key => $menu)
                                        @if(!in_array($key, ['branches', 'users']))
                                        <div class="col-md-6">
                                            <div class="glass-panel p-2">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" name="accessible_menus[]" value="{{ $key }}" id="add_menu_{{ $key }}" checked>
                                                    <label class="form-check-label text-white small" for="add_menu_{{ $key }}">
                                                        <i class="{{ $menu['icon'] ?? 'bi bi-command' }} me-2 text-primary"></i> {{ $menu['label'] }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach

                                    <div class="col-12"><hr class="opacity-10 my-1"></div>

                                    @foreach($dashComponents as $key => $comp)
                                        <div class="col-md-6">
                                            <div class="glass-panel p-2 border-primary border-opacity-10">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" name="accessible_menus[]" value="{{ $key }}" id="add_dash_{{ $key }}" checked>
                                                    <label class="form-check-label text-white small" for="add_dash_{{ $key }}">
                                                        <i class="{{ $comp['icon'] }} me-2 text-warning"></i> {{ $comp['label'] }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-white border-opacity-10 p-4 pt-0">
                        <button type="button" class="btn btn-outline-light px-4 border-0" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-5 shadow-lg">Daftarkan Sekarang!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .sidebar-logo {
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px; shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
        .transition { transition: all 0.3s ease; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .fs-xs { font-size: 0.5rem; }
        .uppercase { text-transform: uppercase; }
        .form-label { letter-spacing: 0.5px; }
    </style>

    <script>
        const vg = document.getElementById('viewGrid'), vt = document.getElementById('viewTable'), gv = document.getElementById('gridView'), tv = document.getElementById('tableView');
        vg?.addEventListener('click', () => { gv.style.display = 'flex'; tv.style.display = 'none'; vg.classList.add('active'); vt.classList.remove('active'); localStorage.setItem('branchView', 'grid'); });
        vt?.addEventListener('click', () => { gv.style.display = 'none'; tv.style.display = 'block'; vt.classList.add('active'); vg.classList.remove('active'); localStorage.setItem('branchView', 'list'); });
        if(localStorage.getItem('branchView') === 'list') vt?.click();
    </script>
</x-app-layout>
