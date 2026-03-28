<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold text-white outfit mb-1">Claim Kerusakan kawan</h2>
                <p class="text-white opacity-50 small mb-0">Catat dan pantau biaya perbaikan armada yang dibebankan ke customer kawan.</p>
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
            <form action="{{ route('payments.claims') }}" method="GET" class="row g-2 align-items-end">
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

        <!-- Spreadsheet Style Table -->
        <div class="glass-panel rounded-4 overflow-hidden border border-white border-opacity-10 shadow-lg">
            <div class="table-responsive">
                <table class="table table-hover table-sm align-middle mb-0 text-white border-0">
                    <thead class="bg-white bg-opacity-5">
                        <tr class="text-white opacity-75 small uppercase outfit border-0">
                            <th class="ps-3 py-3 border-0">Berangkat</th>
                            <th class="border-0">No. SPJ</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Total Klaim</th>
                            <th class="text-center border-0" style="width: 450px;">Input Claim Kerusakan kawan</th>
                        </tr>
                    </thead>
                    <tbody class="border-0 small">
                        @forelse ($jobOrders as $jobOrder)
                        <tr class="border-bottom border-white border-opacity-5">
                            <td class="ps-3 py-2 fw-medium outfit opacity-75">{{ date('d/m/y', strtotime($jobOrder->departure_date)) }}</td>
                            <td class="fw-bold text-primary outfit">{{ $jobOrder->spj_number }}</td>
                            <td class="fw-bold">{{ $jobOrder->customer->name }}</td>
                            <td class="text-danger fw-bold">Rp {{ number_format($jobOrder->claims->sum('amount'), 0, ',', '.') }}</td>
                            <td class="pe-3 text-end">
                                <form action="{{ route('job-orders.claim', $jobOrder->id) }}" method="POST" class="d-flex gap-1 align-items-center mb-0 my-1">
                                    @csrf
                                    <input type="text" name="description" class="form-control form-control-sm bg-dark border-0 text-white w-auto" placeholder="Keterangan..." style="width: 170px !important;" required>
                                    <input type="number" name="amount" class="form-control form-control-sm bg-dark border-0 text-white w-auto fw-bold" placeholder="Nominal Rp" style="width: 130px !important;" required>
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold flex-grow-1">RECORD CLAIM</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-5 text-white opacity-25 italic">Belum ada data kawan.</td></tr>
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
</x-app-layout>
