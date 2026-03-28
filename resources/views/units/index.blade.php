<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-truck fs-4"></i>
            <span>Daftar Unit Operasional</span>
            <span class="badge bg-dark rounded-pill ms-2">{{ $units->total() ?? $units->count() }}</span>
        </div>
    </x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('units.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3">
            <i class="bi bi-plus-lg fs-6"></i> 
            <span class="d-none d-sm-inline">Tambah Unit Baru</span>
        </a>
    </x-slot>

    <div class="card border-0 shadow-lg mt-3">
        <!-- Search & Filter Section -->
        <div class="card-header border-0 py-4 px-4 bg-transparent border-bottom border-white border-opacity-10">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="glass-panel d-flex align-items-center px-3 py-1">
                        <i class="bi bi-search text-muted me-2"></i>
                        <input type="text" id="searchUnit" class="form-control border-0 bg-transparent text-white" placeholder="Cari unit atau nomor polisi kawan..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group p-1 glass-panel" role="group">
                        <button type="button" class="btn btn-primary rounded-pill px-3 py-1" id="viewTable">
                            <i class="bi bi-list-ul me-1"></i> List
                        </button>
                        <button type="button" class="btn btn-link text-muted px-3 py-1 text-decoration-none" id="viewGrid">
                            <i class="bi bi-grid-fill me-1"></i> Grid
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div id="tableView" class="table-container">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom border-white border-opacity-10">
                        <th class="ps-4 py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Unit Informasi</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">No. Polisi</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Chassis & Tahun</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Status</th>
                        <th class="text-end pe-4 py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Aksi Operasional</th>
                    </tr>
                </thead>
                <tbody id="unitTableBody">
                    @forelse($units as $u)
                    <tr class="unit-row border-bottom border-white border-opacity-5 transition">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="sidebar-logo" style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), var(--secondary)); opacity: 0.9;">
                                    <i class="bi bi-truck fs-4 text-white"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-white outfit">{{ $u->name }}</div>
                                    <small class="text-white opacity-50 outfit">ID: {{ $u->unit_code }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-pill bg-white bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold px-3">
                                {{ strtoupper($u->nopol) }}
                            </span>
                        </td>
                        <td>
                            <div class="outfit text-white small mb-1">{{ $u->chassis_number }}</div>
                            <span class="badge rounded-pill bg-light bg-opacity-5 text-white opacity-50 small px-2">Tahun {{ $u->year }}</span>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-20">
                                <i class="bi bi-check2-circle me-1"></i> Ready
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('units.edit', $u->id) }}" class="btn btn-sm btn-outline-primary border-0 bg-white bg-opacity-5 rounded-pill px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                                <form action="{{ route('units.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus unit ini kawan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 bg-white bg-opacity-5 rounded-pill px-3">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center">
                            <i class="bi bi-truck-flatbed fs-1 text-white opacity-10 d-block mb-3"></i>
                            <h5 class="text-white opacity-50 fw-bold">Belum ada armada terdaftar kawan.</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="p-4" style="display: none;">
            <div class="row g-4" id="unitGridBody">
                @forelse($units as $u)
                <div class="col-md-6 col-lg-4 col-xl-3 unit-card">
                    <div class="card h-100 hover-lift transition">
                        <div class="card-body p-4 text-center">
                            <div class="sidebar-logo mx-auto mb-3" style="width: 64px; height: 64px; background: linear-gradient(135deg, var(--primary), var(--secondary));">
                                <i class="bi bi-truck fs-2 text-white"></i>
                            </div>
                            <h5 class="fw-bold text-white mb-2 outfit">{{ $u->name }}</h5>
                            <div class="badge-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 mb-3 px-3 fw-bold">{{ $u->nopol }}</div>
                            
                            <div class="glass-panel p-2 mb-3 text-start small">
                                <div class="text-white opacity-50 mb-1"><i class="bi bi-calendar-check me-2 text-primary"></i>Tahun: {{ $u->year }}</div>
                                <div class="text-truncate text-white opacity-50"><i class="bi bi-fingerprint me-2 text-primary"></i>{{ $u->chassis_number }}</div>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('units.edit', $u->id) }}" class="btn btn-link text-primary p-0 flex-grow-1 text-decoration-none fw-bold small">
                                    <i class="bi bi-pencil-square me-1"></i> EDIT
                                </a>
                                <form action="{{ route('units.destroy', $u->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 w-100 text-decoration-none fw-bold small">
                                        <i class="bi bi-trash3 me-1"></i> HAPUS
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-transparent border-0 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="small text-white opacity-50 outfit">
                    Menampilkan <span class="text-white fw-bold">{{ $units->firstItem() ?? 0 }} - {{ $units->lastItem() ?? 0 }}</span> dari <span class="text-white fw-bold">{{ $units->total() ?? 0 }}</span> unit kawan.
                </div>
                <div class="pagination-premium">
                    {{ $units->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('searchUnit')?.addEventListener('keyup', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.unit-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
        document.querySelectorAll('.unit-card').forEach(card => {
            const text = card.innerText.toLowerCase();
            card.style.display = text.includes(term) ? '' : 'none';
        });
    });

    const viewT = document.getElementById('viewTable');
    const viewG = document.getElementById('viewGrid');
    const tabV = document.getElementById('tableView');
    const griV = document.getElementById('gridView');

    viewT?.addEventListener('click', () => {
        tabV.style.display = 'block'; griV.style.display = 'none';
        viewT.classList.add('active'); viewG.classList.remove('active');
        localStorage.setItem('unitView', 'table');
    });
    viewG?.addEventListener('click', () => {
        tabV.style.display = 'none'; griV.style.display = 'block';
        viewG.classList.add('active'); viewT.classList.remove('active');
        localStorage.setItem('unitView', 'grid');
    });

    if(localStorage.getItem('unitView') === 'grid') viewG?.click();
</script>

<style>
    .hover-lift { transition: all 0.2s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .transition { transition: all 0.3s ease; }
    .table-hover tbody tr:hover { background-color: rgba(44, 62, 80, 0.05); cursor: pointer; }
</style>
