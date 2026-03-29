<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4 align-items-center">
            <div class="col-md-5">
                <h2 class="fw-bold text-white outfit mb-1">Laporan Transaksi kawan</h2>
                <p class="text-white opacity-50 small mb-0">Rekapitulasi keuangan dari seluruh unit kawan.</p>
            </div>
            <div class="col-md-7 text-end">
                <div class="btn-group p-1 bg-black bg-opacity-25 rounded-pill border border-white border-opacity-10 shadow-sm" role="tablist" id="transactionTabs">
                    <button class="btn btn-warning rounded-pill px-3 fw-bold active transition shadow-sm btn-sm" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-pane" type="button" role="tab">
                        SLIDE 1: SEMUA
                    </button>
                    <button class="btn btn-outline-warning border-0 rounded-pill px-3 fw-bold text-white transition ms-1 btn-sm" id="cash-tab" data-bs-toggle="tab" data-bs-target="#cash-pane" type="button" role="tab">
                        SLIDE 2: CASH
                    </button>
                    <button class="btn btn-outline-warning border-0 rounded-pill px-3 fw-bold text-white transition ms-1 btn-sm" id="transfer-tab" data-bs-toggle="tab" data-bs-target="#transfer-pane" type="button" role="tab">
                        SLIDE 3: TRANSFER/CRIS
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="glass-panel p-3 mb-4 rounded-4 shadow-sm border border-white border-opacity-10">
            <form action="{{ route('payments.transactions') }}" method="GET" class="row g-2 align-items-end" id="filterForm">
                <div class="col-md-3">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2 text-uppercase">Filter Customer</label>
                    <select name="customer_id" class="form-select select2-dark border-0 bg-dark text-white rounded-pill">
                        <option value="">-- Semua Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2 text-uppercase">Dari Tgl Bayar</label>
                    <input type="date" name="date_from" class="form-control glass-panel border-0 text-white rounded-pill" value="{{ $date_from }}">
                </div>
                <div class="col-md-2">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2 text-uppercase">Sampai Tgl Bayar</label>
                    <input type="date" name="date_to" class="form-control glass-panel border-0 text-white rounded-pill" value="{{ $date_to }}">
                </div>
                <div class="col-md-3">
                    <label class="text-white opacity-50 small fw-bold mb-1 outfit ms-2 text-uppercase">Cari SPJ</label>
                    <input type="text" name="search" class="form-control glass-panel border-0 text-white rounded-pill" placeholder="..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm"><i class="bi bi-funnel me-1"></i> FILTER</button>
                </div>
            </form>
        </div>

        <div class="tab-content border-0">
            <!-- SLIDE 1: SEMUA -->
            <div class="tab-pane fade show active" id="all-pane" role="tabpanel">
                @include('payments.partials.transaction_slide', [
                    'payments' => $allPayments, 
                    'total' => $totalAll, 
                    'method' => '', 
                    'title' => 'KESELURUHAN',
                    'color' => 'success'
                ])
            </div>

            <!-- SLIDE 2: CASH -->
            <div class="tab-pane fade" id="cash-pane" role="tabpanel">
                @include('payments.partials.transaction_slide', [
                    'payments' => $cashPayments, 
                    'total' => $totalCash, 
                    'method' => 'CASH', 
                    'title' => 'TUNAI (CASH)',
                    'color' => 'warning'
                ])
            </div>

            <!-- SLIDE 3: TRANSFER/CRIS -->
            <div class="tab-pane fade" id="transfer-pane" role="tabpanel">
                @include('payments.partials.transaction_slide', [
                    'payments' => $transferPayments, 
                    'total' => $totalTransfer, 
                    'method' => 'Transfer', 
                    'title' => 'TRANSFER / CRIS',
                    'color' => 'info'
                ])
            </div>
        </div>
    </div>

    @push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .nav-link.active {
            background-color: var(--warning) !important;
            color: black !important;
        }
        .btn-group .btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-group .btn-outline-warning:hover {
            background-color: rgba(255, 193, 7, 0.1);
        }
        .tab-pane.fade {
            transition: opacity 0.3s linear, transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(0.98);
        }
        .tab-pane.show.active {
            transform: scale(1);
        }
        .hover-bg-light:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .trans-slide {
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    @endpush

    @push('js')
    <script>
        $(document).ready(function() {
            $('.select2-dark').select2({
                placeholder: "-- Pilih Customer --",
                allowClear: true,
                theme: 'bootstrap-5',
                dropdownCssClass: "select2-dark-dropdown"
            });

            // Handle Tab Toggle Buttons Styling
            const tabs = ['all', 'cash', 'transfer'];
            tabs.forEach(tab => {
                $(`#${tab}-tab`).on('click', function() {
                    tabs.forEach(t => {
                        $(`#${t}-tab`).removeClass('btn-warning text-dark').addClass('btn-outline-warning text-white');
                    });
                    $(this).removeClass('btn-outline-warning text-white').addClass('btn-warning text-dark');
                });
            });
        });

        function exportData(format, method) {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            if(method) params.append('method', method);
            
            const url = format === 'pdf' ? "{{ route('payments.transactions.pdf') }}" : "{{ route('payments.transactions.excel') }}";
            window.location.href = url + '?' + params.toString();
        }
    </script>
    @endpush
</x-app-layout>
