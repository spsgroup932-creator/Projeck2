<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold text-white outfit mb-1">Laporan SPJ Lunas kawan</h2>
                <p class="text-white opacity-50 small mb-0">Daftar perjalanan yang pembayarannya sudah beres 100% kawan.</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="glass-panel p-3 mb-4 rounded-4 shadow-sm border border-white border-opacity-10">
            <form action="{{ route('payments.settled_report') }}" method="GET" class="row g-2 align-items-end">
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
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2">DARI TANGGAL BERANGKAT</label>
                    <input type="date" name="date_from" class="form-control glass-panel border-0 text-white rounded-pill" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2">SAMPAI TANGGAL BERANGKAT</label>
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
                            <th class="ps-3 py-3 border-0">Berangkat</th>
                            <th class="border-0">No. SPJ</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Unit / Armada</th>
                            <th class="border-0">Total Tagihan</th>
                            <th class="text-center border-0 pe-3">Opsi kawan</th>
                        </tr>
                    </thead>
                    <tbody class="border-0 small">
                        @forelse ($jobOrders as $jobOrder)
                        <tr class="border-bottom border-white border-opacity-5">
                            <td class="ps-3 py-2 fw-medium outfit opacity-75">{{ date('d/m/y', strtotime($jobOrder->departure_date)) }}</td>
                            <td class="fw-bold text-primary outfit">{{ $jobOrder->spj_number }}</td>
                            <td class="fw-bold">{{ $jobOrder->customer->name }}</td>
                            <td class="opacity-75">{{ $jobOrder->unit->name }} ({{ $jobOrder->unit->nopol }})</td>
                            <td class="fw-bold">Rp {{ number_format($jobOrder->total_price + $jobOrder->claims->sum('amount'), 0, ',', '.') }}</td>
                            <td class="text-center pe-3">
                                <div class="d-flex gap-1 justify-content-center">
                                    <form action="{{ route('payments.unsettle', $jobOrder->id) }}" method="POST" onsubmit="return confirm('Batalkan status lunas kawan?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold border-0">BATAL LUNAS</button>
                                    </form>
                                    <a href="{{ route('job-orders.receipt', $jobOrder->id) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" target="_blank">LIHAT NOTA</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-white opacity-25 italic">Belum ada data lunas kawan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($jobOrders->hasPages())
        <div class="mt-4">
            {{ $jobOrders->links() }}
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
