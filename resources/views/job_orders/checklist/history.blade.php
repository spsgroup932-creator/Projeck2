<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clock-history fs-4 text-info"></i>
            <span class="outfit fw-bold">Riwayat Lengkap Checklist Unit</span>
            <span class="badge bg-secondary rounded-pill ms-2 shadow-sm">{{ $checklists->total() }}</span>
        </div>
    </x-slot>

    <div class="card border-0 shadow-lg mt-3 overflow-hidden">
        <div class="card-header border-0 py-4 px-4 bg-transparent border-bottom border-white border-opacity-10">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <form action="{{ route('unit-checklists.history') }}" method="GET">
                        <div class="glass-panel d-flex align-items-center px-3 py-1 shadow-sm">
                            <i class="bi bi-search text-muted me-2"></i>
                            <input type="text" name="search" class="form-control border-0 bg-transparent text-white" placeholder="Cari Nopol atau No. SPJ kawan..." value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="text-white opacity-50 small">
                        <i class="bi bi-calendar-check me-1"></i> Data riwayat permanen kawan.
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-white opacity-50 uppercase small tracking-wider border-bottom border-white border-opacity-10">
                        <th class="ps-4 py-3">Tanggal & Waktu</th>
                        <th>Tipe Cek</th>
                        <th>Armada & No. SPJ</th>
                        <th class="text-center">Kilometer</th>
                        <th class="text-center">BBM</th>
                        <th>Pemeriksa</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($checklists as $log)
                    <tr class="border-bottom border-white border-opacity-5 transition shadow-hover">
                        <td class="ps-4 py-3 text-white">
                            <div class="fw-bold">{{ $log->check_date->format('d/m/Y') }}</div>
                            <small class="opacity-50 fs-xs">{{ $log->check_date->format('H:i') }} WIB</small>
                        </td>
                        <td>
                            @if($log->type === 'departure')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-1">BERANGKAT <i class="bi bi-box-arrow-up ms-1"></i></span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-1">KEMBALI <i class="bi bi-box-arrow-in-down ms-1"></i></span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-white outfit">{{ $log->jobOrder->unit->nopol }}</div>
                            <div class="text-info small fw-bold">{{ $log->jobOrder->spj_number }}</div>
                        </td>
                        <td class="text-center text-white fw-bold">
                            <span class="font-monospace">{{ number_format($log->km_reading) }}</span> <small class="opacity-50 fw-normal">KM</small>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-dark bg-opacity-50 text-warning border border-warning border-opacity-25 px-2">{{ $log->fuel_level }} Bar</span>
                        </td>
                        <td class="text-white">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center border border-info border-opacity-25" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                    {{ strtoupper(substr($log->checker->name, 0, 1)) }}
                                </div>
                                <span class="small opacity-75">{{ $log->checker->name }}</span>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('job-orders.show', $log->job_order_id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 border-white border-opacity-10 fs-xs transition-all hover-primary">
                                <i class="bi bi-file-earmark-text me-1"></i> Detail JO
                            </a>
                        </td>
                    </tr>
                    @if($log->notes)
                    <tr class="bg-white bg-opacity-5 border-0">
                        <td colspan="7" class="ps-4 py-2 border-0">
                            <div class="small text-white opacity-50 px-3 py-1 border-start border-warning italic">
                                <i class="bi bi-sticky me-1"></i> Catatan: {{ $log->notes }}
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="7" class="py-5 text-center text-white opacity-25 italic">Belum ada riwayat tercatat kawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($checklists->hasPages())
        <div class="card-footer bg-transparent border-0 py-4 px-4 overflow-auto">
            {{ $checklists->links() }}
        </div>
        @endif
    </div>

    <style>
        .fs-xs { font-size: 0.72rem; }
        .shadow-hover:hover {
            background-color: rgba(255, 255, 255, 0.03) !important;
        }
        .hover-primary:hover {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: white !important;
        }
    </style>
</x-app-layout>
