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
                                    <form action="{{ route('job-orders.payment', $jobOrder->id) }}" method="POST" class="d-flex gap-1 align-items-center mb-0 my-1 payment-form">
                                        @csrf
                                        <input type="number" name="amount" class="form-control form-control-sm bg-dark border-0 text-white w-auto fw-bold" placeholder="Nominal Rp" style="width: 130px !important;" required>
                                        <select name="method" class="form-select form-select-sm bg-dark border-0 text-white w-auto small method-select" style="width: 110px !important;">
                                            <option value="Cash">Cash</option>
                                            <option value="Transfer">Transfer</option>
                                            <option value="QRIS">QRIS</option>
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

    <!-- Modal Info Pembayaran -->
    <div class="modal fade" id="paymentInfoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="background: var(--bg-card);">
                <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                    <h6 class="modal-title fw-bold outfit text-white" id="paymentInfoTitle">
                        <i class="bi bi-credit-card me-2 text-primary"></i>Info Pembayaran
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <!-- Transfer Bank Info -->
                    <div id="transferInfo" style="display:none;">
                        @php $branch = auth()->user()->branch; @endphp
                        @if($branch && $branch->bank_name)
                            <div class="glass-panel rounded-4 p-4 mb-3">
                                <div class="mb-2"><i class="bi bi-bank fs-1 text-primary"></i></div>
                                <h5 class="fw-bold text-white outfit">{{ $branch->bank_name }}</h5>
                                <div class="bg-dark rounded-3 p-3 my-3 border border-primary border-opacity-25">
                                    <div class="text-muted small text-uppercase fw-bold mb-1">Nomor Rekening</div>
                                    <h4 class="fw-bold text-warning outfit mb-0 user-select-all" id="bankNumber">{{ $branch->bank_account_number }}</h4>
                                </div>
                                <div class="text-muted small">a.n. <span class="fw-bold text-white">{{ $branch->bank_account_name }}</span></div>
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-4 mt-3" onclick="copyToClipboard('{{ $branch->bank_account_number }}')">
                                    <i class="bi bi-clipboard me-1"></i> Salin No. Rekening
                                </button>
                            </div>
                        @else
                            <div class="text-muted py-4">
                                <i class="bi bi-exclamation-circle fs-1 opacity-25 d-block mb-2"></i>
                                <p>Rekening bank belum diatur kawan.<br>Silakan atur di <strong>Pengaturan Rental</strong>.</p>
                            </div>
                        @endif
                    </div>

                    <!-- QRIS Info -->
                    <div id="qrisInfo" style="display:none;">
                        @if($branch && $branch->qris_image)
                            <div class="glass-panel rounded-4 p-4 mb-3">
                                <div class="mb-3"><i class="bi bi-qr-code fs-1 text-success"></i></div>
                                <h5 class="fw-bold text-white outfit mb-3">Scan QRIS untuk Bayar</h5>
                                <div class="bg-white rounded-4 p-3 d-inline-block shadow-sm">
                                    <img src="{{ asset('storage/' . $branch->qris_image) }}" alt="QRIS" class="img-fluid" style="max-height: 250px;">
                                </div>
                                <p class="text-muted small mt-3 mb-0">Gunakan aplikasi bank atau e-wallet kawan untuk scan.</p>
                            </div>
                        @else
                            <div class="text-muted py-4">
                                <i class="bi bi-exclamation-circle fs-1 opacity-25 d-block mb-2"></i>
                                <p>Gambar QRIS belum diupload kawan.<br>Silakan upload di <strong>Pengaturan Rental</strong>.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-top border-white border-opacity-10 p-3">
                    <button type="button" class="btn btn-outline-light border-0 px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
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

            // Show payment info modal when Transfer or QRIS selected
            $(document).on('change', '.method-select', function() {
                const method = $(this).val();
                if (method === 'Transfer') {
                    $('#transferInfo').show();
                    $('#qrisInfo').hide();
                    $('#paymentInfoTitle').html('<i class="bi bi-bank me-2 text-primary"></i>Transfer Bank');
                    new bootstrap.Modal(document.getElementById('paymentInfoModal')).show();
                } else if (method === 'QRIS') {
                    $('#transferInfo').hide();
                    $('#qrisInfo').show();
                    $('#paymentInfoTitle').html('<i class="bi bi-qr-code me-2 text-success"></i>Pembayaran QRIS');
                    new bootstrap.Modal(document.getElementById('paymentInfoModal')).show();
                }
            });
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const btn = event.target.closest('button');
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Tersalin!';
                btn.classList.replace('btn-outline-primary', 'btn-success');
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.classList.replace('btn-success', 'btn-outline-primary');
                }, 2000);
            });
        }
    </script>
    @endpush
</x-app-layout>
