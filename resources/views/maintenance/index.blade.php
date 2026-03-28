<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-white mb-0 outfit">Maintenance Log kawan</h4>
            <p class="text-white opacity-50 mb-0 small">Catatan servis dan perbaikan armada.</p>
        </div>
        <a href="{{ route('maintenance.create') }}" class="btn btn-primary shadow-lg px-4 rounded-pill">
            <i class="bi bi-plus-lg me-2"></i> Tambah Log Servis
        </a>
    </div>
    <div class="card border-0 shadow-lg overflow-hidden mb-4 bg-transparent">
        <div class="card-header border-0 py-3 px-4 bg-black bg-opacity-20 border border-white border-opacity-10 rounded-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="glass-panel d-flex align-items-center px-3 py-1">
                        <i class="bi bi-search text-muted me-2"></i>
                        <input type="text" id="searchLog" class="form-control border-0 bg-transparent text-white" placeholder="Cari unit atau deskripsi kawan..." autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-20 outfit">
                        <i class="bi bi-wrench-adjustable me-1"></i> Total Perbaikan: {{ $logs->total() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0">
                <thead class="text-white opacity-50 small text-uppercase">
                    <tr>
                        <th class="border-0 ps-4">Tanggal Servis</th>
                        <th class="border-0">Unit Armada</th>
                        <th class="border-0">KM Terakhir</th>
                        <th class="border-0">Deskripsi Pekerjaan</th>
                        <th class="border-0 text-end">Biaya (IDR)</th>
                        <th class="border-0 text-center">Bengkel/Mekanik</th>
                        <th class="border-0 text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-white border-0">
                    @forelse($logs as $log)
                    <tr>
                        <td class="border-0 ps-4">
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($log->service_date)->format('d M Y') }}</span>
                        </td>
                        <td class="border-0">
                            <div class="d-flex align-items-center gap-2">
                                <div class="sidebar-logo rounded bg-primary bg-opacity-10 text-primary" style="width: 32px; height: 32px;">
                                    <i class="bi bi-truck small"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $log->unit->nopol }}</div>
                                    <small class="opacity-50 text-uppercase">{{ $log->unit->name }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="border-0">
                            <div class="fw-bold text-info outfit">{{ number_format($log->current_mileage) }} KM</div>
                        </td>
                        <td class="border-0">
                            <div class="text-truncate" style="max-width: 250px;" title="{{ $log->description }}">
                                {{ $log->description }}
                            </div>
                        </td>
                        <td class="border-0 text-end fw-bold text-danger">
                            Rp {{ number_format($log->cost) }}
                        </td>
                        <td class="border-0 text-center">
                            <span class="badge bg-white bg-opacity-5 text-white border border-white border-opacity-10 px-3 py-2">
                                <i class="bi bi-tools me-1 text-warning"></i> {{ $log->mechanic_name ?? '-' }}
                            </span>
                        </td>
                        <td class="border-0 text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('maintenance.edit', $log->id) }}" class="btn btn-sm btn-outline-light border-white border-opacity-10 bg-white bg-opacity-5">Edit</a>
                                <form action="{{ route('maintenance.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus log ini kawan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 opacity-50">Belum ada catatan maintenance kawan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="card-footer border-0 bg-transparent p-4">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
    <script>
        $(document).ready(function() {
            $("#searchLog").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</x-app-layout>
