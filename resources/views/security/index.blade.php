<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-shield-lock-fill fs-4 text-primary"></i>
            <span class="outfit fw-bold uppercase tracking-wider">Audit Trail & Security Logs</span>
            <span class="badge bg-primary rounded-pill ms-2 shadow-sm">{{ $logs->total() }} Events</span>
        </div>
    </x-slot>

    <div class="row g-4">
        {{-- Stats Summary --}}
        <div class="col-md-3">
            <div class="card glass-panel border-0 shadow-lg rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-activity text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="text-white opacity-50 small uppercase fw-bold tracking-tighter">Total Aktifitas</div>
                        <h4 class="fw-bold mb-0 text-white">{{ number_format($logs->total()) }}</h4>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('security.index') }}" method="GET">
                <div class="card glass-panel border-0 shadow-lg rounded-4 p-4">
                    <h6 class="text-white fw-bold mb-3 small uppercase opacity-75">Filter Keamanan</h6>
                    <div class="mb-3">
                        <label class="text-white opacity-50 small mb-1">Cari User/Model</label>
                        <input type="text" name="search" class="form-control border-0 bg-white bg-opacity-10 text-white px-3 py-2 rounded-3" placeholder="Nama user..." value="{{ request('search') }}">
                    </div>
                    <div class="mb-3">
                        <label class="text-white opacity-50 small mb-1">Aksi</label>
                        <select name="action" class="form-select border-0 bg-white bg-opacity-10 text-white px-3 py-2 rounded-3">
                            <option value="">Semua Aksi</option>
                            <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm">
                        Terapkan Filter
                    </button>
                    @if(request()->anyFilled(['search', 'action']))
                        <a href="{{ route('security.index') }}" class="btn btn-link w-100 text-white opacity-50 small mt-2 d-block">Reset Filter</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Log Table --}}
        <div class="col-md-9">
            <div class="card glass-panel border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-white opacity-50 uppercase fs-xs tracking-widest border-bottom border-white border-opacity-10">
                                <th class="ps-4 py-3">Waktu</th>
                                <th>User & Cabang</th>
                                <th>Aksi</th>
                                <th>Objek</th>
                                <th>IP Address</th>
                                <th class="text-end pe-4">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr class="border-bottom border-white border-opacity-5 transition">
                                <td class="ps-4 py-3">
                                    <div class="text-white fw-bold small">{{ $log->created_at->format('d M H:i:s') }}</div>
                                    <div class="text-white opacity-25 tiny">ID: #{{ $log->id }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                                        </div>
                                        <div>
                                            <div class="text-white fw-medium small">{{ $log->user->name ?? 'System' }}</div>
                                            <div class="text-info fw-bold tiny uppercase tracking-tighter">{{ $log->branch->name ?? 'Global' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $bg = 'secondary';
                                        if($log->action == 'created') $bg = 'success';
                                        if($log->action == 'updated') $bg = 'warning';
                                        if($log->action == 'deleted') $bg = 'danger';
                                    @endphp
                                    <span class="badge bg-{{ $bg }} bg-opacity-10 text-{{ $bg }} border border-{{ $bg }} border-opacity-20 px-2 tiny uppercase">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-white small opacity-75">{{ class_basename($log->model_type) }}</div>
                                    <div class="text-white opacity-25 tiny">Ref ID: {{ $log->model_id }}</div>
                                </td>
                                <td>
                                    <div class="text-white opacity-50 tiny font-monospace">{{ $log->ip_address }}</div>
                                </td>
                                <td class="text-end pe-4">
                                    <button type="button" class="btn btn-sm btn-dark bg-white bg-opacity-10 border border-white border-opacity-10 rounded-pill px-3 shadow-sm fs-xs"
                                            onclick="showLogDetail({{ $log->id }})">
                                        LIHAT
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-white opacity-25 italic">Belum ada aktifitas kawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                <div class="card-footer bg-transparent border-0 py-4 px-4 overflow-hidden">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal fade" id="logDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content glass-panel border border-white border-opacity-10 rounded-4 shadow-xl overflow-hidden p-0">
                <div class="modal-header border-bottom border-white border-opacity-10 p-4">
                    <h5 class="modal-title text-white fw-bold outfit uppercase tracking-wider mb-0" id="modalTitle">Detail Perubahan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-white" id="modalBody">
                    <div class="text-center py-5">
                      <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tiny { font-size: 0.65rem; }
        .fs-xs { font-size: 0.75rem; }
        .transition { transition: all 0.2s ease; }
        .table-hover tr:hover { background-color: rgba(255, 255, 255, 0.05) !important; }
    </style>

    <script>
        function showLogDetail(logId) {
            const modal = new bootstrap.Modal(document.getElementById('logDetailModal'));
            const body = document.getElementById('modalBody');
            body.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';
            modal.show();

            fetch(`{{ url('security-logs') }}/${logId}`)
                .then(response => response.text())
                .then(html => {
                    body.innerHTML = html;
                })
                .catch(err => {
                    body.innerHTML = `<div class="alert alert-danger">Error: ${err.message}</div>`;
                });
        }
    </script>
</x-app-layout>
