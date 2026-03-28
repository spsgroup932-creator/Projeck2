<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2 text-white">
            <i class="bi bi-journal-check fs-4 text-success"></i>
            <span>Rekapitulasi Keuangan & Tabulasi Data</span>
        </div>
    </x-slot>

    <div class="row g-4 mt-2">
        <!-- Left: Payments Recap -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg overflow-hidden h-100">
                <div class="card-header border-bottom border-white border-opacity-10 py-3 bg-transparent">
                    <h6 class="fw-bold mb-0 outfit text-success d-flex align-items-center gap-2">
                        <i class="bi bi-wallet2 fs-5"></i> RIWAYAT PEMBAYARAN MASUK
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tanggal</th>
                                    <th>Reference SPJ</th>
                                    <th>Customer</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-center pe-4">Nota</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse ($payments as $p)
                                <tr>
                                    <td class="ps-4 text-white opacity-50">{{ date('d/m/Y', strtotime($p->payment_date)) }}</td>
                                    <td class="fw-bold text-info">{{ $p->jobOrder->spj_number ?? 'N/A' }}</td>
                                    <td class="text-white fw-medium">{{ $p->jobOrder->customer->name ?? 'N/A' }}</td>
                                    <td class="text-end fw-bold text-success">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('payments.download', $p->id) }}" class="btn btn-dark btn-sm rounded-circle shadow-sm" title="Download PDF">
                                            <i class="bi bi-file-pdf text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-5 text-white opacity-50 italic">Belum ada transaksi pembayaran masuk kawan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top border-white border-opacity-10 bg-transparent py-3 px-4">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>

        <!-- Right: Claims Recap -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg overflow-hidden h-100">
                <div class="card-header border-bottom border-white border-opacity-10 py-3 bg-transparent">
                    <h6 class="fw-bold mb-0 outfit text-danger d-flex align-items-center gap-2">
                        <i class="bi bi-shield-exclamation fs-5"></i> REKAPAN CLAIM KERUSAKAN
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">No. SPJ</th>
                                    <th>Customer</th>
                                    <th class="text-end pe-4">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse ($claims as $c)
                                <tr>
                                    <td class="ps-4 fw-bold text-warning">{{ $c->jobOrder->spj_number ?? 'N/A' }}</td>
                                    <td class="text-white opacity-75">{{ $c->jobOrder->customer->name ?? 'N/A' }}</td>
                                    <td class="text-end text-danger fw-bold pe-4">Rp {{ number_format($c->amount, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-5 text-white opacity-50 italic">Belum ada claim kerusakan terdaftar kawan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
