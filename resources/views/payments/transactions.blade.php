<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold text-white outfit mb-1">Rekap Transaksi (Uang Masuk) kawan</h2>
                <p class="text-white opacity-50 small mb-0">Total Pendapatan Terfilter: <span class="text-success fw-bold h5 ms-2">Rp {{ number_format($totalMoney, 0, ',', '.') }}</span></p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="glass-panel p-3 mb-4 rounded-4 shadow-sm border border-white border-opacity-10">
            <form action="{{ route('payments.transactions') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2">FILTER CUSTOMER kawan</label>
                    <select name="customer_id" class="form-select select2-dark border-0 bg-dark text-white rounded-pill">
                        <option value="">-- Semua Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2">DARI TANGGAL BAYAR</label>
                    <input type="date" name="date_from" class="form-control glass-panel border-0 text-white rounded-pill" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2">SAMPAI TANGGAL BAYAR</label>
                    <input type="date" name="date_to" class="form-control glass-panel border-0 text-white rounded-pill" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2">CARI SPJ</label>
                    <input type="text" name="search" class="form-control glass-panel border-0 text-white rounded-pill" placeholder="..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2"><i class="bi bi-funnel me-1"></i> FILTER</button>
                </div>
            </form>
        </div>

        <!-- Spreadsheet Style Table -->
        <div class="glass-panel rounded-4 overflow-hidden border border-white border-opacity-10 shadow-lg">
            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle mb-0 text-white border-0">
                    <thead class="bg-white bg-opacity-5">
                        <tr class="text-white opacity-75 small uppercase outfit border-0">
                            <th class="ps-3 py-3 border-0">Tanggal Bayar</th>
                            <th class="border-0">No. SPJ</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Metode</th>
                            <th class="border-0 text-end pe-3">Nominal Masuk kawan</th>
                        </tr>
                    </thead>
                    <tbody class="border-0 small">
                        @forelse ($payments as $p)
                        <tr class="border-bottom border-white border-opacity-5">
                            <td class="ps-3 py-2 fw-medium outfit opacity-75">{{ date('d/m/y', strtotime($p->payment_date)) }}</td>
                            <td class="fw-bold text-primary outfit">{{ $p->jobOrder->spj_number }}</td>
                            <td class="fw-bold">{{ $p->jobOrder->customer->name }}</td>
                            <td><span class="badge bg-white bg-opacity-10 text-white px-3 rounded-pill">{{ $p->payment_method }}</span></td>
                            <td class="text-end pe-3 fw-bold text-success">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-white opacity-25 italic">Belum ada transaksi kawan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($payments->hasPages())
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
        @endif
    </div>

    @push('js')
    <script>
        $(document).ready(function() {
            $('.select2-dark').select2({
                placeholder: "-- Pilih Customer --",
                allowClear: true,
                theme: 'bootstrap-5',
                dropdownCssClass: "select2-dark-dropdown"
            });
        });
    </script>
    @endpush
</x-app-layout>
