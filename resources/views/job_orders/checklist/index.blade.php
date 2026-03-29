<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-check-fill fs-4 text-primary"></i>
            <span class="outfit fw-bold">Daftar Tunggu Checklist Unit</span>
            <span class="badge bg-danger rounded-pill ms-2 shadow-sm">{{ $jobOrders->total() }}</span>
        </div>
    </x-slot>

    <div class="card border-0 shadow-lg mt-3 overflow-hidden">
        <div class="card-header border-0 py-4 px-4 bg-transparent border-bottom border-white border-opacity-10">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <form action="{{ route('unit-checklists.index') }}" method="GET">
                        <div class="glass-panel d-flex align-items-center px-3 py-1 shadow-sm">
                            <i class="bi bi-search text-muted me-2"></i>
                            <input type="text" name="search" class="form-control border-0 bg-transparent text-white" placeholder="Cari Nopol atau No. SPJ kawan..." value="{{ request('search') }}" autocomplete="off">
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 border border-warning border-opacity-25 rounded-pill fs-xs uppercase fw-bold outfit">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $jobOrders->total() }} Unit Perlu Dicek
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-white opacity-50 uppercase small tracking-wider border-bottom border-white border-opacity-10">
                        <th class="ps-4 py-3">No. SPJ & Tanggal</th>
                        <th>Unit Rental</th>
                        <th>Customer / Tujuan</th>
                        <th class="text-center">Status Checklist</th>
                        <th class="text-end pe-4">Aksi kawan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobOrders as $spj)
                    @php
                        $hasDeparture = $spj->checklists->where('type', 'departure')->first();
                        $hasReturn = $spj->checklists->where('type', 'return')->first();
                    @endphp
                    <tr class="border-bottom border-white border-opacity-5 transition">
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-primary outfit">{{ $spj->spj_number }}</div>
                            <small class="text-white opacity-50 fs-xs italic">{{ \Carbon\Carbon::parse($spj->departure_date)->format('d M Y') }}</small>
                        </td>
                        <td>
                            <div class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-2 py-1 mb-1 fw-bold">{{ $spj->unit->nopol }}</div>
                            <div class="text-white small fw-medium outfit">{{ $spj->unit->name }}</div>
                        </td>
                        <td>
                            <div class="text-white fw-bold small">{{ $spj->customer->name }}</div>
                            <div class="text-white opacity-50 fs-xs text-truncate" style="max-width: 150px;">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $spj->destination }}
                            </div>
                        </td>
                        <td class="text-center">
                            @if(!$hasDeparture)
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-3 py-2 shadow-sm animate-pulse-slow">
                                    <i class="bi bi-box-arrow-up me-1"></i> BELUM CHECK-OUT
                                </span>
                            @elseif(!$hasReturn)
                                <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-3 py-2">
                                    <i class="bi bi-truck me-1"></i> BELUM CHECK-IN
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if(!$hasDeparture)
                                <a href="{{ route('unit-checklists.create', ['job_order_id' => $spj->id, 'type' => 'departure']) }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
                                    Mulai Check-out
                                </a>
                            @elseif(!$hasReturn)
                                <a href="{{ route('unit-checklists.create', ['job_order_id' => $spj->id, 'type' => 'return']) }}" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
                                    Proses Check-in
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center">
                            <div class="text-white opacity-10 mb-3"><i class="bi bi-check2-circle display-4"></i></div>
                            <h6 class="text-white opacity-50 fw-bold outfit uppercase tracking-widest">Semua unit sudah di-checklist kawan!</h6>
                            <small class="text-white opacity-25">Kilas balik kawan. Tidak ada tugas pending.</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobOrders->hasPages())
        <div class="card-footer bg-transparent border-0 py-4 px-4">
            {{ $jobOrders->links() }}
        </div>
        @endif
    </div>

    <style>
        .fs-xs { font-size: 0.73rem; }
        .animate-pulse-slow {
            animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }
    </style>
</x-app-layout>
