<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
            <span>Daftar Job Order (SPJ)</span>
            <span class="badge bg-primary rounded-pill ms-2">{{ $jobOrders->total() ?? $jobOrders->count() }}</span>
        </div>
    </x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('job-orders.create') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3">
            <i class="bi bi-plus-circle-fill fs-6"></i> 
            <span class="d-none d-sm-inline">Buat SPJ Baru</span>
        </a>
    </x-slot>

    <div class="card border-0 shadow-lg mt-3">
        <!-- Search & Info Section -->
        <div class="card-header border-0 py-4 px-4 bg-transparent border-bottom border-white border-opacity-10">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="glass-panel d-flex align-items-center px-3 py-1">
                        <i class="bi bi-search text-muted me-2"></i>
                        <input type="text" id="searchSpj" class="form-control border-0 bg-transparent text-white" placeholder="Cari No. SPJ atau Customer kawan..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3 py-2 border border-primary border-opacity-20 outfit">
                        <i class="bi bi-activity me-1"></i> Operasional Aktif
                    </span>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="table-container">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="border-bottom border-white border-opacity-10">
                        <th class="ps-4 py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">No. SPJ & Tanggal</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Customer & Tujuan</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Armada / Driver</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider text-center">Payment</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider text-end">Total Biaya</th>
                        <th class="py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider text-end">Terbayar</th>
                        <th class="text-end pe-4 py-4 text-white opacity-50 small fw-bold outfit uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="spjTableBody">
                    @forelse($jobOrders as $spj)
                    <tr class="spj-row border-bottom border-white border-opacity-5 transition">
                        <td class="ps-4 py-4">
                            <div class="fw-bold text-primary outfit fs-6">{{ $spj->spj_number }}</div>
                            <div class="text-white opacity-50 small outfit"><i class="bi bi-calendar3 me-1"></i>{{ $spj->departure_date }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-white outfit">{{ $spj->customer->name }}</div>
                            <div class="text-white opacity-50 small text-truncate" style="max-width: 150px;">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $spj->destination }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-white bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold">
                                    {{ $spj->unit->nopol }}
                                </span>
                            </div>
                            <div class="text-white opacity-50 small outfit">
                                <i class="bi bi-person-badge me-1"></i>{{ $spj->driver->name }}
                            </div>
                        </td>
                        <td class="text-center">
                            @if($spj->payment_status == 'Lunas')
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3">
                                    <i class="bi bi-check-circle-fill me-1"></i> LUNAS
                                </span>
                            @else
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-3">
                                    <i class="bi bi-hourglass-split me-1"></i> PENDING
                                </span>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-white outfit">
                            Rp{{ number_format($spj->total_price, 0, ',', '.') }}
                        </td>
                        <td class="text-end">
                            <div class="text-info fw-bold outfit">Rp{{ number_format($spj->paid_amount, 0, ',', '.') }}</div>
                            @if($spj->remaining_balance > 0)
                                <small class="text-danger outfit opacity-75">-Rp{{ number_format($spj->remaining_balance, 0, ',', '.') }}</small>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('job-orders.show', $spj->id) }}" class="btn btn-sm btn-outline-primary border-0 bg-white bg-opacity-5 rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                                <form action="{{ route('job-orders.destroy', $spj->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus SPJ ini kawan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 bg-white bg-opacity-5 rounded-pill">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-5 text-center">
                            <i class="bi bi-file-earmark-x fs-1 text-white opacity-10 d-block mb-3"></i>
                            <h5 class="text-white opacity-50 fw-bold">Belum ada Job Order aktif kawan.</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobOrders->hasPages())
        <div class="card-footer bg-transparent border-0 py-4 px-4">
            <div class="pagination-premium">
                {{ $jobOrders->links() }}
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
