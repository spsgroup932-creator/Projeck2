<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-steering-wheel fs-4"></i>
            <span>Daftar Driver & Personel</span>
            <span class="badge bg-primary rounded-pill ms-2">{{ $drivers->total() ?? $drivers->count() }}</span>
        </div>
    </x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('drivers.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3">
            <i class="bi bi-person-plus-fill fs-6"></i> 
            <span class="d-none d-sm-inline">Registrasi Driver</span>
        </a>
    </x-slot>

    <div class="card border-0 shadow-lg mt-3">
        <!-- Search & Filter Section -->
        <div class="card-header border-0 py-4 px-4 bg-transparent border-bottom border-white border-opacity-10">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="glass-panel d-flex align-items-center px-3 py-1">
                        <i class="bi bi-search text-white opacity-25 me-2"></i>
                        <input type="text" id="searchDriver" class="form-control border-0 bg-transparent text-white" placeholder="Cari nama atau ID driver kawan..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="btn-group p-1 glass-panel" role="group">
                        <button type="button" class="btn btn-primary rounded-pill px-3 py-1" id="viewTable">
                            <i class="bi bi-list-ul me-1"></i> List
                        </button>
                        <button type="button" class="btn btn-link text-white opacity-50 px-3 py-1 text-decoration-none" id="viewGrid">
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
                        <th class="ps-4 py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Profil Driver</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Kode / ID</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Usia & Dokumen</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Kontak & Alamat</th>
                        <th class="text-end pe-4 py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Aksi Operasional</th>
                    </tr>
                </thead>
                <tbody id="driverTableBody">
                    @forelse($drivers as $d)
                    <tr class="driver-row border-bottom border-white border-opacity-5 transition">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="sidebar-logo shadow-lg" style="width: 44px; height: 44px; background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); font-size: 1.2rem;">
                                    {{ strtoupper(substr($d->name, 0, 1)) }}
                                </div>
                                <div class="fw-bold text-white outfit">{{ $d->name }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-pill bg-white bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold px-3">
                                {{ $d->driver_code }}
                            </span>
                        </td>
                        <td>
                            <div class="outfit text-white small mb-1">{{ $d->age }} Tahun</div>
                            @if($d->ktp_photo)
                                <a href="{{ asset('storage/'.$d->ktp_photo) }}" target="_blank" class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-20 text-decoration-none small">
                                    <i class="bi bi-file-earmark-check me-1"></i> KTP Verified
                                </a>
                            @else
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 small">
                                    <i class="bi bi-x-circle me-1"></i> No KTP
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="text-white small mb-1"><i class="bi bi-telephone-fill text-primary me-2"></i>{{ $d->phone ?? '-' }}</div>
                            <small class="text-white opacity-50 text-truncate d-inline-block small" style="max-width: 250px;">{{ $d->address }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('drivers.edit', $d->id) }}" class="btn btn-sm btn-outline-primary border-0 bg-white bg-opacity-5 rounded-pill px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                                <form action="{{ route('drivers.destroy', $d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sopir ini kawan?')">
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
                            <i class="bi bi-person-x fs-1 text-white opacity-10 d-block mb-3"></i>
                            <h5 class="text-white opacity-50 fw-bold">Belum ada sopir terdaftar kawan.</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="p-4" style="display: none;">
            <div class="row g-4" id="driverGridBody">
                @forelse($drivers as $d)
                <div class="col-md-6 col-lg-4 col-xl-3 driver-card">
                    <div class="card h-100 hover-lift transition">
                        <div class="card-body p-4 text-center">
                            <div class="sidebar-logo mx-auto mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); font-size: 1.5rem;">
                                {{ strtoupper(substr($d->name, 0, 1)) }}
                            </div>
                            <h5 class="fw-bold text-white mb-2 outfit">{{ $d->name }}</h5>
                            <div class="badge-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 mb-3 px-3 fw-bold">{{ $d->driver_code }}</div>
                            
                            <div class="glass-panel p-2 mb-3 text-start small">
                                <div class="text-white mb-1"><i class="bi bi-calendar-event me-2 text-primary"></i>Usia: {{ $d->age }} Th</div>
                                <div class="text-truncate text-white opacity-50"><i class="bi bi-geo-alt-fill me-2 text-primary"></i>{{ $d->address }}</div>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('drivers.edit', $d->id) }}" class="btn btn-link text-primary p-0 flex-grow-1 text-decoration-none fw-bold small">
                                    <i class="bi bi-pencil-square me-1"></i> EDIT
                                </a>
                                <form action="{{ route('drivers.destroy', $d->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Hapus?')">
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
                    Menampilkan <span class="text-white fw-bold">{{ $drivers->firstItem() ?? 0 }} - {{ $drivers->lastItem() ?? 0 }}</span> dari <span class="text-white fw-bold">{{ $drivers->total() ?? 0 }}</span> driver kawan.
                </div>
                <div class="pagination-premium">
                    {{ $drivers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('searchDriver')?.addEventListener('keyup', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.driver-row').forEach(row => { row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none'; });
        document.querySelectorAll('.driver-card').forEach(card => { card.style.display = card.innerText.toLowerCase().includes(term) ? '' : 'none'; });
    });
    const vt = document.getElementById('viewTable'), vg = document.getElementById('viewGrid'), tv = document.getElementById('tableView'), gv = document.getElementById('gridView');
    vt?.addEventListener('click', () => { tv.style.display = 'block'; gv.style.display = 'none'; vt.classList.add('active'); vg.classList.remove('active'); localStorage.setItem('driverView', 'table'); });
    vg?.addEventListener('click', () => { tv.style.display = 'none'; gv.style.display = 'block'; vg.classList.add('active'); vt.classList.remove('active'); localStorage.setItem('driverView', 'grid'); });
    if(localStorage.getItem('driverView') === 'grid') vg?.click();
</script>

<style>
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .transition { transition: all 0.3s ease; }
    .table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.05); cursor: pointer; }
</style>
