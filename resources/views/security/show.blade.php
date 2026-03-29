<div class="activity-log-detail pb-3">
    <div class="row g-3 mb-4">
        <div class="col-md-6 border-end border-white border-opacity-10 py-2">
            <div class="text-white opacity-50 small uppercase fw-bold tracking-tighter mb-1">Aksi & Objek</div>
            <div class="fw-bold fs-5 text-white outfit">
                <span class="text-primary">{{ strtoupper($log->action) }}</span> 
                {{ class_basename($log->model_type) }}
            </div>
            <div class="text-white opacity-25 tiny">Model: {{ $log->model_type }} (ID: {{ $log->model_id }})</div>
        </div>
        <div class="col-md-6 py-2">
            <div class="text-white opacity-50 small uppercase fw-bold tracking-tighter mb-1">Pelaksana & Lokasi</div>
            <div class="text-white fw-bold outfit fs-6">
                {{ $log->user->name ?? 'System' }} 
                <span class="text-info opacity-75 small fw-normal ms-2">@ {{ $log->branch->name ?? 'Global' }}</span>
            </div>
            <div class="text-white opacity-25 tiny">IP: {{ $log->ip_address }} | {{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i:s') }}</div>
        </div>
    </div>

    @if($log->old_values || $log->new_values)
        <div class="comparison-table mt-4 rounded-3 overflow-hidden border border-white border-opacity-10 shadow-sm">
            <table class="table table-dark table-sm mb-0">
                <thead>
                    <tr class="bg-white bg-opacity-10">
                        <th class="ps-3 py-2 small fw-bold opacity-75">Field / Atribut</th>
                        <th class="py-2 small fw-bold text-danger opacity-75">Nilai Lama</th>
                        <th class="py-2 small fw-bold text-success opacity-75">Nilai Baru</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $fields = collect(array_merge(array_keys($log->old_values ?? []), array_keys($log->new_values ?? [])))->unique();
                        // Ignore some fields for cleaner view
                        $fields = $fields->reject(fn($f) => in_array($f, ['updated_at', 'created_at', 'password', 'remember_token']));
                    @endphp
                    @foreach($fields as $field)
                        <tr>
                            <td class="ps-3 py-2 text-white opacity-50 small fw-bold uppercase fs-xs">{{ $field }}</td>
                            <td class="py-2 small">
                                <span class="text-danger text-truncate d-inline-block" style="max-width: 250px;">
                                    {{ is_array($log->old_values[$field] ?? '') ? json_encode($log->old_values[$field]) : ($log->old_values[$field] ?? '-') }}
                                </span>
                            </td>
                            <td class="py-2 small">
                                <span class="text-success text-truncate d-inline-block fw-bold" style="max-width: 250px;">
                                    {{ is_array($log->new_values[$field] ?? '') ? json_encode($log->new_values[$field]) : ($log->new_values[$field] ?? '-') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-4 text-white opacity-25 italic small border border-dashed border-white border-opacity-25 rounded-3">
            <i class="bi bi-info-circle me-1"></i> Tidak ada perubahan atribut detail yang dicatat kawan.
        </div>
    @endif

    <div class="mt-4 p-3 rounded-3 bg-white bg-opacity-5 border border-white border-opacity-10 shadow-sm">
        <div class="text-white opacity-50 small uppercase fw-bold tracking-tighter mb-2 fs-xs">User Agent</div>
        <div class="text-white opacity-25 tiny font-monospace line-clamp-2">{{ $log->user_agent }}</div>
    </div>
</div>

<style>
    .fs-xs { font-size: 0.7rem; }
    .tiny { font-size: 0.65rem; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
