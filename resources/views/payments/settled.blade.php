<x-app-layout>
    <div class="container-fluid py-4 font-sans text-white">
        <div class="row mb-4 animate__animated animate__fadeInDown">
            <div class="col-12">
                <h2 class="fw-bold mb-1"><i class="bi bi-shield-check text-success me-2"></i>Data Terbayar Lunas</h2>
                <p class="text-secondary small mb-0">List of all job orders and SPJs that have been fully settled kawan.</p>
            </div>
        </div>

        <!-- Search Section -->
        <div class="card bg-black bg-opacity-25 border border-secondary border-opacity-25 rounded-4 shadow-sm mb-4">
            <div class="card-body p-3">
                <form action="{{ route('payments.settled') }}" method="GET" class="row g-2">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control bg-dark border-secondary text-white" 
                               placeholder="Cari SPJ atau Customer yang sudah lunas kawan..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100 fw-bold">Cari Data</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4">
            @forelse ($jobOrders as $jobOrder)
            <div class="col-12">
                <div class="card bg-black bg-opacity-25 border border-success border-opacity-20 rounded-4 overflow-hidden shadow-sm">
                    <div class="card-body p-0">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-2 p-4 text-center border-end border-secondary border-opacity-10">
                                <span class="badge bg-success bg-opacity-25 text-success small mb-1 px-3 rounded-pill fw-bold">TERBAYAR</span>
                                <div class="text-white fw-black h5 mb-0">{{ $jobOrder->spj_number }}</div>
                            </div>
                            <div class="col-md-3 p-4">
                                <div class="text-secondary small text-uppercase mb-1">Customer</div>
                                <div class="text-white fw-bold">{{ $jobOrder->customer->name }}</div>
                            </div>
                            <div class="col-md-3 p-4">
                                <div class="text-secondary small text-uppercase mb-1">Tujuan</div>
                                <div class="text-white fw-bold">{{ $jobOrder->destination }}</div>
                            </div>
                            <div class="col-md-2 p-4">
                                <div class="text-secondary small text-uppercase mb-1">Total Lunas</div>
                                <div class="text-info fw-bold">Rp {{ number_format($jobOrder->total_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-md-2 p-4 text-end">
                                <form action="{{ route('payments.unsettle', $jobOrder->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger rounded-pill px-3 fw-bold me-2" onclick="return confirm('Batalkan status Lunas kawan?')">
                                        <i class="bi bi-arrow-counterclockwise"></i> Batal Lunas
                                    </button>
                                </form>
                                <button class="btn btn-outline-info rounded-pill px-4 fw-bold" 
                                        type="button" data-bs-toggle="collapse" data-bs-target="#paySection{{ $jobOrder->id }}">
                                    <i class="bi bi-eye me-1"></i> Rincian
                                </button>
                            </div>
                        </div>

                        <!-- History Section (Collapse) -->
                        <div class="collapse border-top border-secondary border-opacity-10" id="paySection{{ $jobOrder->id }}">
                            <div class="p-4 bg-dark bg-opacity-25">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-success fw-bold mb-3 small tracking-wider">RIWAYAT PEMBAYARAN:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-dark table-sm mb-0">
                                                <tbody class="small">
                                                    @foreach ($jobOrder->payments as $p)
                                                    <tr>
                                                        <td class="text-secondary">{{ date('d/m/Y', strtotime($p->payment_date)) }}</td>
                                                        <td class="text-white">Metode: {{ $p->method }}</td>
                                                        <td class="text-success fw-bold text-end">+Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                                                        <td class="text-end">
                                                            <a href="{{ route('payments.download', $p->id) }}" class="btn btn-link btn-sm text-info"><i class="bi bi-file-pdf"></i> Nota</a>
                                                            <a href="https://wa.me/{{ $p->jobOrder->customer->phone }}?text=Halo%20{{ urlencode($p->jobOrder->customer->name) }},%20ini%20bukti%20pembayaran%20lunas%20Anda%20untuk%20SPJ%20{{ $p->jobOrder->spj_number }}.%20Terima%20kasih%20kawan!" 
                                                               class="btn btn-link btn-sm text-success ms-2" target="_blank"><i class="bi bi-whatsapp"></i> WA</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="text-secondary h4 italic">Belum ada data SPJ yang ditandai lunas kawan.</div>
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $jobOrders->links() }}
        </div>
    </div>
</x-app-layout>
