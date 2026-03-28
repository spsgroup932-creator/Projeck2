<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold text-white outfit mb-1">Manajemen Pembayaran</h2>
                <p class="text-white opacity-50 small mb-0">Kelola transaksi dan pantau piutang operasional kawan.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2 border border-warning border-opacity-20 outfit">
                    <i class="bi bi-wallet2 me-1"></i> Billing Center
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show glass-panel border-0 border-start border-4 border-success rounded-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                    <div>
                        <div class="fw-bold text-white outfit">Berhasil kawan!</div>
                        <div class="text-white opacity-50 small">{{ session('success') }}</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="glass-panel p-3 mb-4 rounded-4 shadow-sm border border-white border-opacity-10">
            <form action="{{ route('payments.index') }}" method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="{{ request('tab', 'pending') }}">
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
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2">DARI TANGGAL</label>
                    <input type="date" name="date_from" class="form-control glass-panel border-0 text-white rounded-pill" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2">SAMPAI TANGGAL</label>
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

        <!-- Tabs Section -->
        <ul class="nav nav-tabs border-bottom border-white border-opacity-10 mb-3" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ !request('tab') || request('tab') == 'pending' ? 'active bg-white bg-opacity-10' : '' }} text-white fw-bold outfit border-0 px-4" 
                   href="{{ route('payments.index', array_merge(request()->query(), ['tab' => 'pending'])) }}">TAGIHAN AKTIF</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'lunas' ? 'active bg-white bg-opacity-10' : '' }} text-white fw-bold outfit border-0 px-4" 
                   href="{{ route('payments.index', array_merge(request()->query(), ['tab' => 'lunas'])) }}">DATA LUNAS kawan</a>
            </li>
        </ul>

        <!-- Spreadsheet Style Table -->
        <div class="glass-panel rounded-4 overflow-hidden border border-white border-opacity-10 shadow-lg">
            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle mb-0 text-white border-0">
                    <thead class="bg-white bg-opacity-5">
                        <tr class="text-white opacity-75 small uppercase outfit border-0">
                            <th class="ps-3 py-3 border-0">Berangkat</th>
                            <th class="border-0">No. SPJ</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Total</th>
                            <th class="border-0">Sisa Bayar</th>
                            <th class="text-center border-0" style="width: 450px;">Input Pembayaran kawan</th>
                        </tr>
                    </thead>
                    <tbody class="border-0 small">
                        @forelse ($jobOrders as $jobOrder)
                        <tr class="border-bottom border-white border-opacity-5">
                            <td class="ps-3 py-2 fw-medium outfit opacity-75">{{ date('d/m/y', strtotime($jobOrder->departure_date)) }}</td>
                            <td class="fw-bold text-primary outfit">{{ $jobOrder->spj_number }}</td>
                            <td class="fw-bold">{{ $jobOrder->customer->name }}</td>
                            <td class="opacity-75">Rp {{ number_format($jobOrder->total_price, 0, ',', '.') }}</td>
                            <td class="text-danger fw-bold outfit">Rp {{ number_format($jobOrder->remaining_balance, 0, ',', '.') }}</td>
                            <td class="pe-3">
                                @if($jobOrder->payment_status != 'Lunas' && $jobOrder->remaining_balance > 0)
                                    <form action="{{ route('job-orders.payment', $jobOrder->id) }}" method="POST" class="d-flex gap-1 align-items-center mb-0 my-1">
                                        @csrf
                                        <input type="number" name="amount" class="form-control form-control-sm bg-dark border-0 text-white w-auto fw-bold" placeholder="Nominal Rp" style="width: 130px !important;" required>
                                        <select name="method" class="form-select form-select-sm bg-dark border-0 text-white w-auto small" style="width: 100px !important;">
                                            <option value="Transfer">Transfer</option>
                                            <option value="Cash">Cash</option>
                                        </select>
                                        <input type="date" name="payment_date" class="form-control form-control-sm bg-dark border-0 text-white w-auto small" value="{{ date('Y-m-d') }}" style="width: 120px !important;" required>
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold flex-grow-1">RECORD</button>
                                    </form>
                                @elseif($jobOrder->payment_status != 'Lunas' && $jobOrder->remaining_balance <= 0)
                                    <form action="{{ route('payments.settle', $jobOrder->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100 rounded-pill fw-bold py-1" onclick="return confirm('Sudah Lunas kawan?')">TANDAI LUNAS kawan!</button>
                                    </form>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success w-100 py-2 rounded-pill fw-bold">TERBAYAR LUNAS kawan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-white opacity-25 italic">Belum ada data kawan.</td></tr>
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
