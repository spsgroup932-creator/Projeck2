<x-app-layout>
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 bg-black bg-opacity-20 p-4 rounded-4 border border-white border-opacity-10 shadow-sm">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h4 class="text-success fw-bold mb-0">{{ $jobOrder->closing_number }}</h4>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2 rounded-pill small">CLOSED</span>
                </div>
                <div class="text-white opacity-50 small">Ref SPJ: <span class="text-white">{{ $jobOrder->spj_number }}</span> • Closed on {{ \Carbon\Carbon::parse($jobOrder->closing_date)->format('d M Y') }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('job-orders.download-pdf', $jobOrder->id) }}" class="btn btn-success btn-sm px-4 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-download me-1"></i> Download PDF
                </a>
                <a href="{{ route('job-orders.receipt', $jobOrder->id) }}" target="_blank" class="btn btn-warning btn-sm px-4 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-receipt me-1"></i> Cetak Struk
                </a>
                <a href="{{ route('job-orders.closed') }}" class="btn btn-outline-secondary btn-sm px-4 rounded-3 text-white border-white border-opacity-20">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <form action="{{ route('job-orders.unclose', $jobOrder->id) }}" method="POST" onsubmit="return confirm('Apakah kawan yakin ingin membatalkan closing ini? SPJ akan kembali ke daftar Open.')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm px-4 rounded-3 fw-bold">
                        <i class="bi bi-unlock-fill me-1"></i> Batal Closing
                    </button>
                </form>
                <button onclick="window.print()" class="btn btn-warning btn-sm px-4 rounded-3 fw-bold text-dark shadow-sm">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Report
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Order Info -->
            <div class="col-12 col-xl-5">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle-fill text-warning"></i>
                            <h6 class="fw-bold mb-0 text-white text-uppercase tracking-wider fs-7">Informasi Job Order</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">No. SPJ</label>
                                <div class="text-warning fw-bold">{{ $jobOrder->spj_number }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Customer</label>
                                <div class="text-white">{{ $jobOrder->customer->name }}</div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Tujuan</label>
                                <div class="text-white">{{ $jobOrder->destination }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Kendaraan</label>
                                <div class="text-white fw-bold">{{ strtoupper($jobOrder->unit->name) }} ({{ $jobOrder->unit->nopol }})</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Driver</label>
                                <div class="text-white">{{ $jobOrder->driver->name }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Berangkat</label>
                                <div class="text-white">{{ \Carbon\Carbon::parse($jobOrder->departure_date)->format('d/m/Y') }} {{ $jobOrder->departure_time }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Kembali</label>
                                <div class="text-white">{{ \Carbon\Carbon::parse($jobOrder->return_date)->format('d/m/Y') }}</div>
                            </div>
                            <div class="col-6">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Durasi</label>
                                <div class="text-white">{{ $jobOrder->days_count }} hari</div>
                            </div>
                            <div class="col-6">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Sales/Market</label>
                                <div class="text-white">{{ $jobOrder->sales_market ?? '-' }}</div>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-white bg-opacity-5 rounded-3 border border-white border-opacity-10">
                            <label class="text-white opacity-50 small text-uppercase fw-bold mb-2 d-block">Catatan</label>
                            <div class="text-white small">{{ $jobOrder->description ?? 'Tidak ada catatan.' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Financial Record -->
            <div class="col-12 col-xl-7 d-flex flex-column gap-4">
                <!-- Financial Summary -->
                <div class="card bg-black bg-opacity-25 border border-secondary border-opacity-25 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3 px-4">
                        <h6 class="fw-bold mb-0 text-white text-uppercase tracking-wider"><i class="bi bi-calculator me-2 text-warning"></i> Ringkasan Keuangan</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 bg-white bg-opacity-5 rounded-3 border border-white border-opacity-5">
                                    <label class="text-white opacity-50 small text-uppercase d-block mb-1">Total Tarif</label>
                                    <div class="text-white fw-bold fs-5">Rp {{ number_format($jobOrder->total_price, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-info bg-opacity-10 rounded-3 border border-info border-opacity-10">
                                    <label class="text-white opacity-50 small text-uppercase d-block mb-1">Total Bayar</label>
                                    <div class="text-info fw-bold fs-5">Rp {{ number_format($jobOrder->paid_amount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-{{ $jobOrder->payment_status == 'Lunas' ? 'success' : 'danger' }} bg-opacity-10 rounded-3 border border-{{ $jobOrder->payment_status == 'Lunas' ? 'success' : 'danger' }} border-opacity-10">
                                    <label class="text-white opacity-50 small text-uppercase d-block mb-1">Status</label>
                                    <div class="text-{{ $jobOrder->payment_status == 'Lunas' ? 'success' : 'danger' }} fw-bold fs-5">{{ strtoupper($jobOrder->payment_status) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                             <label class="text-white opacity-50 small fw-bold text-uppercase mb-2 d-block">Riwayat Pembayaran</label>
                             <div class="table-responsive rounded-3 border border-secondary border-opacity-10">
                                <table class="table table-dark table-hover mb-0">
                                    <thead class="small text-white opacity-50">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Metode</th>
                                            <th class="text-end">Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small">
                                        @foreach($jobOrder->payments as $payment)
                                            <tr>
                                                <td class="text-white opacity-50">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                                <td><span class="badge bg-secondary bg-opacity-25">{{ $payment->method }}</span></td>
                                                <td class="text-end text-info fw-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                             </div>
                        </div>
                    </div>
                </div>

                <!-- Claims -->
                @if($jobOrder->claims->count() > 0)
                <div class="card bg-black bg-opacity-25 border border-secondary border-opacity-25 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3 px-4">
                        <h6 class="fw-bold mb-0 text-white text-uppercase tracking-wider"><i class="bi bi-hammer me-2 text-danger"></i> Claim Kerusakan</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-3 border border-secondary border-opacity-10">
                            <table class="table table-dark table-hover mb-0">
                                <thead class="small text-white opacity-50">
                                    <tr>
                                        <th>Keterangan</th>
                                        <th class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach($jobOrder->claims as $claim)
                                        <tr>
                                            <td class="text-white opacity-50">{{ $claim->description }}</td>
                                            <td class="text-end text-danger fw-bold">Rp {{ number_format($claim->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold bg-white bg-opacity-5">
                                        <td class="text-white">TOTAL CLAIM</td>
                                        <td class="text-end text-danger fs-6">Rp {{ number_format($jobOrder->claims->sum('amount'), 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media print {
            .bg-dark, body { background: white !important; color: black !important; }
            .btn, .sidebar, header, .navbar { display: none !important; }
            .card { border: 1px solid #ddd !important; background: transparent !important; box-shadow: none !important; color: black !important; }
            .text-white, .text-warning, .text-info { color: black !important; }
            .text-white opacity-50 { color: #555 !important; }
            .bg-black { background: transparent !important; }
            .badge { border: 1px solid #000 !important; color: black !important; background: transparent !important; }
            .container-fluid { padding: 0 !important; }
        }
    </style>
</x-app-layout>
