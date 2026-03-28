<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-check fs-4 text-success"></i>
            <span>Data Job Order (Closed)</span>
            <span class="badge bg-success rounded-pill ms-2">{{ $jobOrders->total() ?? $jobOrders->count() }}</span>
        </div>
    </x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('job-orders.report') }}" class="btn btn-sm btn-outline-success d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3 me-2">
            <i class="bi bi-file-earmark-bar-graph"></i> Rekapan
        </a>
        <a href="{{ route('job-orders.index') }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3">
            <i class="bi bi-arrow-left-circle"></i> 
            <span class="d-none d-sm-inline">Kembali ke Open SPJ</span>
        </a>
    </x-slot>

    <div class="card mb-4 border-0 shadow-sm overflow-hidden text-white mt-1">
        <div class="card-header border-0 py-3 px-4 bg-transparent border-bottom border-white border-opacity-10">

            <div class="row align-items-center">
                <div class="col-md-6 text-dark fw-bold">Histori Job Order Selesai</div>
                <div class="col-md-6 text-md-end">
                    <div class="input-group overflow-hidden border-secondary border-opacity-25 border">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="bi bi-search text-secondary"></i>
                        </span>
                        <input type="text" id="searchClosedSpj" class="form-control bg-transparent border-0 ps-0" placeholder="Cari No. Closing atau SPJ..." autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-dark">No. Closing</th>
                        <th class="py-3 text-dark">No. SPJ</th>
                        <th class="py-3 text-dark">Customer</th>
                        <th class="py-3 text-dark">Unit & Driver</th>
                        <th class="py-3 text-dark">Tgl Closing</th>
                        <th class="py-3 text-end pe-4 text-dark">Total</th>
                        <th class="text-end pe-4 py-3 text-dark">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobOrders as $spj)
                    <tr class="spj-row">
                        <td class="ps-4">
                            <div class="fw-bold text-success">{{ $spj->closing_number }}</div>
                        </td>
                        <td>
                            <div class="text-primary small fw-medium">{{ $spj->spj_number }}</div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $spj->customer->name }}</div>
                            <small class="text-muted">{{ $spj->customer->phone }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-dark border">{{ $spj->unit->nopol }}</span>
                                <span class="text-secondary small">/ {{ $spj->driver->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-medium">{{ \Carbon\Carbon::parse($spj->closing_date)->format('d M Y') }}</div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="fw-bold text-dark">Rp{{ number_format($spj->total_price, 0, ',', '.') }}</div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group shadow-sm rounded-3 overflow-hidden border-0">
                                <a href="{{ route('job-orders.show-closed', $spj->id) }}" class="btn btn-light border-0 py-2 px-3" title="Detail">
                                    <i class="bi bi-eye text-info"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-5 text-center text-muted">Belum ada Job Order yang di-closing kawan.</td>
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
