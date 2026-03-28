<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-people fs-4"></i>
            <span>Daftar Customer</span>
            <span class="badge bg-primary rounded-pill ms-2">{{ $customers->total() ?? $customers->count() }}</span>
        </div>
    </x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('customers.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3">
            <i class="bi bi-person-plus fs-6"></i> 
            <span class="d-none d-sm-inline">Tambah Pelanggan</span>
        </a>
    </x-slot>

    <div class="card border-0 shadow-lg mt-3">
        <!-- Search & Filter Section -->
        <div class="card-header border-0 py-4 px-4 bg-transparent border-bottom border-white border-opacity-10">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="glass-panel d-flex align-items-center px-3 py-1">
                        <i class="bi bi-search text-white opacity-25 me-2"></i>
                        <input type="text" id="searchCustomer" class="form-control border-0 bg-transparent text-white" placeholder="Cari nama atau WhatsApp pelanggan kawan..." autocomplete="off">
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
                        <th class="ps-4 py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Profil Pelanggan</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Kontak / WA</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Member ID</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Status Dokumen</th>
                        <th class="text-end pe-4 py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Aksi Operasional</th>
                    </tr>
                </thead>
                <tbody id="customerTableBody">
                    @forelse($customers as $c)
                    <tr class="customer-row border-bottom border-white border-opacity-5 transition">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="sidebar-logo shadow-lg" style="width: 44px; height: 44px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 1.2rem;">
                                    {{ strtoupper(substr($c->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-white outfit">{{ $c->name }}</div>
                                    <small class="text-white opacity-50 outfit small">{{ $c->email ?? 'No Email' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-whatsapp text-success"></i>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $c->phone) }}" 
                                   class="text-decoration-none text-white small opacity-50 hover-lift" target="_blank">
                                    {{ $c->phone }}
                                </a>
                            </div>
                        </td>
                        <td>
                            <span class="badge-pill bg-white bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold px-3">
                                {{ $c->customer_code }}
                            </span>
                        </td>
                        <td>
                            @if($c->ktp_photo)
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-20 small">
                                    <i class="bi bi-shield-check me-1"></i> Verified
                                </span>
                            @else
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 small">
                                    <i class="bi bi-clock me-1"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('customers.show', $c->id) }}" class="btn btn-sm btn-outline-info border-0 bg-white bg-opacity-5 rounded-pill px-3">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-sm btn-outline-primary border-0 bg-white bg-opacity-5 rounded-pill px-3">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelanggan ini kawan?')">
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
                            <i class="bi bi-people fs-1 text-white opacity-10 d-block mb-3"></i>
                            <h5 class="text-white opacity-50 fw-bold">Belum ada pelanggan terdaftar kawan.</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="p-4" style="display: none;">
            <div class="row g-4" id="customerGridBody">
                @forelse($customers as $c)
                <div class="col-md-6 col-lg-4 col-xl-3 customer-card">
                    <div class="card h-100 hover-lift transition">
                        <div class="card-body p-4 text-center">
                            <div class="sidebar-logo mx-auto mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 1.5rem;">
                                {{ strtoupper(substr($c->name, 0, 1)) }}
                            </div>
                            <h5 class="fw-bold text-white mb-2 outfit">{{ $c->name }}</h5>
                            <div class="badge-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 mb-3 px-3 fw-bold">{{ $c->customer_code }}</div>
                            
                            <div class="glass-panel p-2 mb-3 text-start small">
                                <div class="text-white mb-1"><i class="bi bi-whatsapp me-2 text-success"></i>{{ $c->phone }}</div>
                                <div class="text-truncate text-white opacity-50"><i class="bi bi-envelope me-2 text-primary"></i>{{ $c->email ?? 'No Email' }}</div>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('customers.show', $c->id) }}" class="btn btn-link text-info p-0 flex-grow-1 text-decoration-none fw-bold small">
                                    <i class="bi bi-eye me-1"></i> VIEW
                                </a>
                                <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-link text-primary p-0 flex-grow-1 text-decoration-none fw-bold small">
                                    <i class="bi bi-pencil-square me-1"></i> EDIT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if(isset($customers) && method_exists($customers, 'links'))
        <div class="card-footer bg-transparent border-0 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="small text-white opacity-50 outfit">
                    Menampilkan <span class="text-white fw-bold">{{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }}</span> dari <span class="text-white fw-bold">{{ $customers->total() ?? 0 }}</span> pelanggan kawan.
                </div>
                <div class="pagination-premium">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>

<script>
    // Search functionality
    document.getElementById('searchCustomer')?.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        // For table view
        const tableRows = document.querySelectorAll('#customerTableBody .customer-row');
        tableRows.forEach(row => {
            const name = row.querySelector('.fw-bold')?.innerText.toLowerCase() || '';
            const phone = row.querySelector('.text-secondary.small')?.innerText.toLowerCase() || '';
            const matches = name.includes(searchTerm) || phone.includes(searchTerm);
            row.style.display = matches ? '' : 'none';
        });
        
        // For grid view
        const gridCards = document.querySelectorAll('#customerGridBody .customer-card');
        gridCards.forEach(card => {
            const name = card.querySelector('h5')?.innerText.toLowerCase() || '';
            const phone = card.querySelector('.small')?.innerText.toLowerCase() || '';
            const matches = name.includes(searchTerm) || phone.includes(searchTerm);
            card.style.display = matches ? '' : 'none';
        });
    });
    
    // Toggle between table and grid view
    const viewTableBtn = document.getElementById('viewTable');
    const viewGridBtn = document.getElementById('viewGrid');
    const tableView = document.getElementById('tableView');
    const gridView = document.getElementById('gridView');
    
    viewTableBtn?.addEventListener('click', function() {
        viewTableBtn.classList.add('active');
        viewGridBtn.classList.remove('active');
        tableView.style.display = 'block';
        gridView.style.display = 'none';
        localStorage.setItem('customerViewPreference', 'table');
    });
    
    viewGridBtn?.addEventListener('click', function() {
        viewGridBtn.classList.add('active');
        viewTableBtn.classList.remove('active');
        tableView.style.display = 'none';
        gridView.style.display = 'block';
        localStorage.setItem('customerViewPreference', 'grid');
    });
    
    // Load saved preference
    const savedView = localStorage.getItem('customerViewPreference');
    if (savedView === 'grid') {
        viewGridBtn?.click();
    }
</script>

<style>
    /* Custom Animations & Styles */
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0,0,0,0.1) !important;
    }
    
    .transition {
        transition: all 0.3s ease;
    }
    
    .bg-gradient-soft {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .hover-whatsapp:hover {
        color: #25D366 !important;
    }
    
    /* Table hover effect */
    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        cursor: pointer;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn-group .btn span {
            display: none;
        }
        
        .btn-group .btn i {
            margin: 0;
        }
        
        .card-header .row > div:first-child {
            margin-bottom: 10px;
        }
    }
    
    
    /* Custom scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
</style>