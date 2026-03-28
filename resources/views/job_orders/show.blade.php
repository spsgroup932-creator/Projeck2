<x-app-layout>
    <div class="container-fluid p-0">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-10 border-success border-opacity-25 text-success rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-10 border-danger border-opacity-25 text-danger rounded-4 mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 bg-black bg-opacity-20 p-4 rounded-4 border border-white border-opacity-10 shadow-sm">
            <div>
                <h4 class="text-warning fw-bold mb-1">{{ $jobOrder->spj_number }}</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-white opacity-50 small">{{ $jobOrder->customer->name }}</span>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-3 rounded-pill small">
                        {{ $jobOrder->payment_status }}
                    </span>
                    @if($jobOrder->is_closed)
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 py-1 px-3 rounded-pill small">
                            CLOSED
                        </span>
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2 print-hide">
                <a href="{{ route('job-orders.download-pdf', $jobOrder->id) }}" class="btn btn-success btn-sm px-4 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-download me-1"></i> Download PDF
                </a>
                <a href="{{ route('job-orders.receipt', $jobOrder->id) }}" target="_blank" class="btn btn-warning btn-sm px-4 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-receipt me-1"></i> Cetak Struk
                </a>
                <button onclick="window.print()" class="btn btn-outline-warning btn-sm px-4 rounded-3 text-white border-white border-opacity-20 d-none d-md-inline-block">
                    <i class="bi bi-file-pdf me-1"></i> Screenshot Page
                </button>
                <a href="{{ route('job-orders.index') }}" class="btn btn-outline-secondary btn-sm px-4 rounded-3 text-white border-white border-opacity-20">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
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
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Telepon</label>
                                <div class="text-white">{{ $jobOrder->customer->phone }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Tujuan</label>
                                <div class="text-white">{{ $jobOrder->destination }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Kendaraan</label>
                                <div class="text-white fw-bold">{{ strtoupper($jobOrder->unit->name) }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">No. Polisi</label>
                                <div class="badge bg-secondary bg-opacity-25 text-white border border-secondary border-opacity-50 px-2 py-1">{{ $jobOrder->unit->nopol }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Driver</label>
                                <div class="text-white d-flex align-items-center gap-1">
                                    <i class="bi bi-person-fill text-warning"></i> {{ $jobOrder->driver->name }}
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Jam Jemput</label>
                                <div class="text-white">{{ $jobOrder->departure_time }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Berangkat</label>
                                <div class="text-white">{{ $jobOrder->departure_date }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Kembali</label>
                                <div class="text-white">{{ $jobOrder->return_date }}</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Durasi</label>
                                <div class="text-white">{{ $jobOrder->days_count }} hari</div>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Harga / Hari</label>
                                <div class="text-white">Rp {{ number_format($jobOrder->price_per_day, 0, ',', '.') }}</div>
                            </div>
                             <div class="col-6">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Market</label>
                                <div class="text-white">{{ $jobOrder->sales_market ?? '-' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Keterangan</label>
                                <div class="text-white small">{{ $jobOrder->description ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Financials & Claims -->
            <div class="col-12 col-xl-7 d-flex flex-column gap-4">

                <!-- Section 1: Tarif & Pembayaran -->
                <div class="card bg-black bg-opacity-25 border border-secondary border-opacity-25 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-wallet2 text-warning"></i>
                            <h6 class="fw-bold mb-0 text-white text-uppercase tracking-wider">Tarif & Pembayaran</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-0 text-center border border-secondary border-opacity-25 rounded-3 overflow-hidden mb-4">
                            <div class="col-3 p-3 border-end border-secondary border-opacity-25">
                                <label class="text-white opacity-50 small text-uppercase d-block mb-1">Total Tarif</label>
                                <div class="text-white fw-bold">Rp {{ number_format($jobOrder->total_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-3 p-3 border-end border-secondary border-opacity-25">
                                <label class="text-white opacity-50 small text-uppercase d-block mb-1">Terbayar</label>
                                <div class="text-info fw-bold">Rp {{ number_format($jobOrder->paid_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-3 p-3 border-end border-secondary border-opacity-25">
                                <label class="text-white opacity-50 small text-uppercase d-block mb-1">Sisa</label>
                                <div class="text-danger fw-bold">Rp {{ number_format($jobOrder->remaining_balance, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-3 p-3 bg-{{ $jobOrder->payment_status == 'Lunas' ? 'success' : 'warning' }} bg-opacity-10">
                                <label class="text-white opacity-50 small text-uppercase d-block mb-1">Status</label>
                                <span class="badge bg-{{ $jobOrder->payment_status == 'Lunas' ? 'success' : 'warning' }} px-3">{{ $jobOrder->payment_status }}</span>
                            </div>
                        </div>

                        <!-- Form Tambah Pembayaran -->
                        <form action="{{ route('job-orders.payment', $jobOrder->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_date" value="{{ date('Y-m-d') }}">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label text-white opacity-50 small fw-bold">JUMLAH (RP)</label>
                                    <input type="number" name="amount" class="form-control bg-dark border-secondary text-white rounded-2" placeholder="0" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-white opacity-50 small fw-bold">METODE</label>
                                    <select name="method" class="form-select bg-dark border-white border-opacity-10 text-white rounded-2">
                                        <option value="Transfer">Transfer</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-warning w-100 fw-bold text-dark rounded-2 py-2">Simpan</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <label class="text-white opacity-50 small fw-bold text-uppercase mb-2 d-block">Riwayat Pembayaran</label>
                            <table class="table table-dark table-hover mb-0 border border-secondary border-opacity-10 rounded overflow-hidden">
                                <thead class="small text-white opacity-50">
                                    <tr>
                                        <th class="border-secondary border-opacity-25 pb-2">Tanggal</th>
                                        <th class="border-secondary border-opacity-25 pb-2">Metode</th>
                                        <th class="border-secondary border-opacity-25 pb-2 text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @forelse ($jobOrder->payments as $payment)
                                        <tr>
                                            <td class="text-white opacity-50 py-2">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                                            <td class="py-2"><span class="badge bg-secondary bg-opacity-25 text-white small">{{ $payment->method }}</span></td>
                                            <td class="text-end text-info fw-bold py-2">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-3 text-white opacity-50 italic">- Belum ada riwayat pembayaran -</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Claim Kerusakan -->
                <div class="card bg-black bg-opacity-25 border border-secondary border-opacity-25 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-hammer text-warning"></i>
                            <h6 class="fw-bold mb-0 text-white text-uppercase tracking-wider">Lepas Kunci & Claim Kerusakan</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('job-orders.claim', $jobOrder->id) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="row g-2 align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label text-white opacity-50 small fw-bold text-uppercase">Keterangan</label>
                                    <input type="text" name="description" class="form-control bg-dark border-secondary text-white rounded-2" placeholder="Misal: Ban bocor, body lecet" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-white opacity-50 small fw-bold text-uppercase">Nominal</label>
                                    <input type="number" name="amount" class="form-control bg-dark border-secondary text-white rounded-2" placeholder="Rp 0" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-warning w-100 fw-bold text-dark rounded-2 py-2">Simpan</button>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <label class="text-white opacity-50 small fw-bold text-uppercase mb-2 d-block">Riwayat Claim</label>
                            <table class="table table-dark table-hover mb-0 border border-secondary border-opacity-10 rounded overflow-hidden">
                                <thead class="small text-white opacity-50">
                                    <tr>
                                        <th class="border-secondary border-opacity-25 pb-2">Deskripsi Kerusakan</th>
                                        <th class="border-secondary border-opacity-25 pb-2 text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @forelse ($jobOrder->claims as $claim)
                                        <tr>
                                            <td class="text-white opacity-50 py-2">{{ $claim->description }}</td>
                                            <td class="text-end text-danger fw-bold py-2">Rp {{ number_format($claim->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-3 text-white opacity-50 italic">- Belum ada riwayat claim -</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Closing -->
                <div class="card bg-black bg-opacity-25 border border-secondary border-opacity-25 rounded-4 shadow-sm">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25 py-3 px-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-warning"></i>
                            <h6 class="fw-bold mb-0 text-white text-uppercase tracking-wider">Closing & Tanda Tangan</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if($jobOrder->is_closed)
                            <div class="bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-3 mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <label class="text-white opacity-50 small text-uppercase fw-bold mb-1">Status Closing</label>
                                    <div class="text-success fw-bold fs-5">CLOSED</div>
                                </div>
                                <div class="text-end">
                                    <div class="text-white small mb-1">{{ $jobOrder->closing_number }}</div>
                                    <div class="text-white opacity-50 small italic">{{ $jobOrder->closing_date }}</div>
                                </div>
                            </div>
                            @if($jobOrder->digital_signature)
                                <div class="mt-3 text-center p-3 bg-white bg-opacity-5 rounded-3 border border-white border-opacity-10">
                                    <label class="text-white opacity-50 small text-uppercase fw-bold mb-2 d-block">Tanda Tangan Digital</label>
                                    <img src="{{ $jobOrder->digital_signature }}" alt="Signature" style="max-height: 150px; filter: invert(1) brightness(2);">
                                </div>
                            @endif
                        @else
                            <form action="{{ route('job-orders.close', $jobOrder->id) }}" method="POST" id="closingForm">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-white opacity-50 small fw-bold">NO. CLOSING</label>
                                        <input type="text" class="form-control bg-dark bg-opacity-50 border-secondary text-white opacity-50 rounded-2" value="CLS-{{ $jobOrder->spj_number }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white opacity-50 small fw-bold">TANGGAL CLOSING</label>
                                        <input type="date" name="closing_date" class="form-control bg-dark border-secondary text-white rounded-2" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    
                                    <div class="col-12 mt-3">
                                        <label class="form-label text-white opacity-50 small fw-bold text-uppercase d-block mb-2">Tanda Tangan Digital kawan</label>
                                        <div class="signature-wrapper bg-white bg-opacity-5 rounded-3 border border-secondary border-opacity-25 overflow-hidden position-relative" style="height: 200px;">
                                            <canvas id="signature-pad" class="w-100 h-100" style="cursor: crosshair; touch-action: none;"></canvas>
                                            <button type="button" id="clear-signature" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-2">
                                                <i class="bi bi-eraser me-1"></i> Bersihkan
                                            </button>
                                        </div>
                                        <input type="hidden" name="digital_signature" id="digital_signature_data">
                                        <small class="text-white opacity-50 mt-1 d-block"><i class="bi bi-info-circle me-1"></i> Silakan tanda tangan langsung di atas canvas kawan.</small>
                                    </div>

                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-warning px-5 fw-bold text-dark rounded-pill py-2" onclick="return validateSignature()">
                                            <i class="bi bi-lock-fill me-1"></i> Close Order & Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        .font-sans { font-family: 'Inter', sans-serif; }
        .tracking-wider { letter-spacing: 0.05em; }
        
        .form-control:focus, .form-select:focus {
            background-color: #1a1a1a !important;
            border-color: #ffc107 !important;
            color: white !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.1) !important;
        }

        .btn-warning { background-color: #ffc107 !important; color: #000 !important; }
        .btn-warning:hover { background-color: #e0ac00 !important; }

        .card { backdrop-filter: blur(10px); }

        /* Removing scrollbar arrows for number inputs */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        @media print {
            .bg-dark { background: white !important; color: black !important; padding: 0 !important; }
            .btn, form, .closing-step-btn, .sidebar, header { display: none !important; }
            .card { border: 1px solid #eee !important; background: transparent !important; box-shadow: none !important; }
            .text-white, .text-warning, .text-info { color: black !important; }
            .text-white opacity-50 { color: #666 !important; }
            .card-body { padding: 1rem !important; }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-pad');
            if (canvas) {
                const signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: '#ffffff'
                });

                // Resize canvas kawan
                function resizeCanvas() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                    signaturePad.clear();
                }

                window.addEventListener("resize", resizeCanvas);
                resizeCanvas();

                document.getElementById('clear-signature')?.addEventListener('click', function() {
                    signaturePad.clear();
                });

                window.validateSignature = function() {
                    if (signaturePad.isEmpty()) {
                        alert("Harap isi tanda tangan kawan!");
                        return false;
                    }
                    const dataUrl = signaturePad.toDataURL();
                    document.getElementById('digital_signature_data').value = dataUrl;
                    return confirm('Apakah kawan yakin ingin menutup order ini?');
                };
            }
        });
    </script>
</x-app-layout>
