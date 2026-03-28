<x-app-layout>
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 bg-black bg-opacity-20 p-4 rounded-4 border border-white border-opacity-10 shadow-sm print-hide">
            <div>
                <h4 class="text-primary fw-bold mb-1">Rekapan Job Order (SPJ)</h4>
                <div class="text-secondary small">Laporan ringkasan data operasional yang sudah ditutup kawan.</div>
            </div>
            <div class="d-flex flex-grow-1 justify-content-center px-4">
                <form action="{{ route('job-orders.report') }}" method="GET" class="d-flex align-items-end gap-2 bg-white bg-opacity-5 p-2 rounded-3 border border-white border-opacity-10">
                    <div>
                        <label class="text-secondary small fw-bold d-block mb-1" style="font-size: 0.6rem;">DARI TANGGAL</label>
                        <input type="date" name="start_date" class="form-control form-control-sm bg-dark border-secondary text-white" value="{{ request('start_date') }}">
                    </div>
                    <div>
                        <label class="text-secondary small fw-bold d-block mb-1" style="font-size: 0.6rem;">SAMPAI TANGGAL</label>
                        <input type="date" name="end_date" class="form-control form-control-sm bg-dark border-secondary text-white" value="{{ request('end_date') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                    @if(request()->has('start_date') || request()->has('end_date'))
                        <a href="{{ route('job-orders.report') }}" class="btn btn-outline-secondary btn-sm px-2">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('job-orders.export', request()->all()) }}" class="btn btn-success btn-sm px-4 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                </a>
                <button onclick="window.print()" class="btn btn-primary btn-sm px-4 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-file-pdf me-1"></i> Cetak PDF
                </button>
            </div>
        </div>

        <!-- Report Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-dark text-white text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <tr>
                                <th class="ps-4 py-3">No. SPJ</th>
                                <th class="py-3">Customer</th>
                                <th class="py-3">Berangkat</th>
                                <th class="py-3">Kembali</th>
                                <th class="py-3 text-center">Hari</th>
                                <th class="py-3 text-end">Tarif Total</th>
                                <th class="py-3 text-end">Terbayar</th>
                                <th class="py-3 text-end">Claim</th>
                                <th class="py-3 text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jobOrders as $spj)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-warning small">{{ $spj->spj_number }}</div>
                                        <div class="text-muted" style="font-size: 0.65rem;">{{ $spj->closing_number }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-white">{{ $spj->customer->name }}</div>
                                    </td>
                                    <td><span class="text-secondary small">{{ \Carbon\Carbon::parse($spj->departure_date)->format('d/m/Y') }}</span></td>
                                    <td><span class="text-secondary small">{{ \Carbon\Carbon::parse($spj->return_date)->format('d/m/Y') }}</span></td>
                                    <td class="text-center"><span class="badge bg-secondary bg-opacity-10 text-white rounded-pill">{{ $spj->days_count }}</span></td>
                                    <td class="text-end fw-bold text-white text-nowrap">Rp{{ number_format($spj->total_price, 0, ',', '.') }}</td>
                                    <td class="text-end text-nowrap">
                                        <span class="fw-bold {{ $spj->payment_status == 'Lunas' ? 'text-success' : 'text-info' }}">
                                            Rp{{ number_format($spj->paid_amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="text-end text-danger fw-bold text-nowrap">
                                        Rp{{ number_format($spj->claims->sum('amount'), 0, ',', '.') }}
                                    </td>
                                    <td class="text-center pe-4">
                                        @if($spj->payment_status == 'Lunas')
                                            <span class="badge bg-success px-2 py-1 small">LUNAS</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-2 py-1 small">{{ strtoupper($spj->payment_status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-5 text-center text-muted">Belum ada data rekapan kawan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .print-hide { display: none !important; }
            .bg-dark, thead { background: #333 !important; color: white !important; }
            body { background: white !important; color: black !important; }
            .card { border: 1px solid #eee !important; box-shadow: none !important; }
            .table td, .table th { border: 1px solid #eee !important; color: black !important; padding: 8px !important; }
            .text-white, .text-warning, .text-info { color: black !important; }
            .badge { border: 1px solid #000 !important; color: black !important; background: transparent !important; }
            .text-success { color: green !important; }
            .text-danger { color: red !important; }
        }
    </style>
</x-app-layout>
