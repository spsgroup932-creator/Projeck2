<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <h5 class="text-white opacity-75 fw-bold mb-0 outfit">DATA TRANSAKSI {{ $title }}</h5>
    </div>
    <div class="col-md-6 text-end d-flex justify-content-end gap-2">
        <div class="p-1 glass-panel rounded-pill d-inline-flex align-items-center px-3 border border-white border-opacity-10 me-3">
            <span class="text-white opacity-50 small me-2">Total {{ $title }}:</span>
            <span class="text-{{ $color }} fw-bold h6 mb-0">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
        <button onclick="exportData('pdf', '{{ $method }}')" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-file-pdf me-1"></i> PDF
        </button>
        <button onclick="exportData('excel', '{{ $method }}')" class="btn btn-success btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-file-earmark-excel me-1"></i> EXCEL
        </button>
    </div>
</div>

<div class="glass-panel rounded-4 overflow-hidden border border-white border-opacity-10 shadow-lg transition trans-slide">
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle mb-0 text-white border-0">
            <thead class="bg-white bg-opacity-5">
                <tr class="text-white opacity-75 small uppercase outfit border-0">
                    <th class="ps-3 py-3 border-0">Tanggal Bayar</th>
                    <th class="border-0">No. SPJ</th>
                    <th class="border-0">Customer</th>
                    <th class="border-0">Unit</th>
                    <th class="border-0">Metode</th>
                    <th class="border-0 text-end pe-3">Nominal kawan</th>
                </tr>
            </thead>
            <tbody class="border-0 small text-white opacity-90">
                @forelse ($payments as $p)
                <tr class="border-bottom border-white border-opacity-5 hover-bg-light transition">
                    <td class="ps-3 py-2 fw-medium outfit opacity-75">{{ date('d/m/y', strtotime($p->payment_date)) }}</td>
                    <td class="fw-bold text-{{ $color }} outfit">{{ $p->jobOrder->spj_number }}</td>
                    <td>{{ $p->jobOrder->customer->name }}</td>
                    <td class="small opacity-75">{{ $p->jobOrder->unit->name }}</td>
                    <td>
                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 px-3 rounded-pill">
                            {{ $p->method }}
                        </span>
                    </td>
                    <td class="text-end pe-3 fw-bold text-{{ $color }}">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-5 text-white opacity-25 italic text-uppercase">Belum ada data transaksi {{ $title }} kawan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
