<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-hdd-network-fill fs-4 text-warning"></i>
            <span class="outfit fw-bold uppercase tracking-wider">Database Backup Center</span>
        </div>
    </x-slot>

    <div class="row g-4">
        {{-- Control Panel --}}
        <div class="col-md-4">
            <div class="card glass-panel border-0 shadow-lg rounded-4 p-4 mb-4 text-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-4 d-inline-flex mb-3">
                    <i class="bi bi-shield-lock-fill text-warning fs-1"></i>
                </div>
                <h5 class="text-white fw-bold outfit uppercase mb-2">Amankan Data kawan</h5>
                <p class="text-white opacity-50 small mb-4">
                    Selalu lakukan backup database secara rutin untuk menghindari kehilangan data yang tidak diinginkan kawan.
                </p>
                
                <form action="{{ route('backups.generate') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning w-100 rounded-pill py-3 fw-bold shadow-lg transform-hover btn-animate">
                        <i class="bi bi-plus-circle me-2"></i> GENERATE BACKUP BARU
                    </button>
                </form>
            </div>

            <div class="card glass-panel border-0 shadow-lg rounded-4 p-4 border border-info border-opacity-10">
                <h6 class="text-info fw-bold mb-3 small uppercase tracking-tighter"><i class="bi bi-info-circle me-2"></i>Informasi Backup</h6>
                <ul class="list-unstyled mb-0 text-white small opacity-75">
                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Format File: <strong>.SQL</strong></li>
                    <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Lokasi: <strong>/storage/app/backups</strong></li>
                    <li class="mb-0"><i class="bi bi-check2 text-success me-2"></i> Username: <strong>{{ config('database.connections.mysql.username') }}</strong></li>
                </ul>
            </div>
        </div>

        {{-- Backup History --}}
        <div class="col-md-8">
            <div class="card glass-panel border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-header border-0 bg-transparent border-bottom border-white border-opacity-10 p-4">
                    <h6 class="text-white fw-bold mb-0 uppercase tracking-wider fs-7">Riwayat File Backup</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="text-white opacity-50 uppercase fs-xs tracking-widest border-bottom border-white border-opacity-10">
                                <th class="ps-4 py-3">Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Backup</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($backups as $backup)
                            <tr class="border-bottom border-white border-opacity-5 transition">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded bg-dark p-2 border border-white border-opacity-10">
                                            <i class="bi bi-file-earmark-code text-info fs-5"></i>
                                        </div>
                                        <div class="text-white fw-bold small text-truncate" style="max-width: 250px;">{{ $backup['name'] }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-white bg-opacity-10 text-white rounded-pill px-3">{{ $backup['size'] }}</span>
                                </td>
                                <td>
                                    <div class="text-white small opacity-75">{{ $backup['date'] }}</div>
                                </td>
                                <td class="text-end pe-4 d-flex gap-2 justify-content-end">
                                    <a href="{{ route('backups.download', $backup['name']) }}" class="btn btn-sm btn-info rounded-pill px-3 py-1 shadow-sm fs-xs fw-bold text-white">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <form action="{{ route('backups.destroy', $backup['name']) }}" method="POST" onsubmit="return confirm('Hapus file backup ini kawan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm fs-xs fw-bold text-white">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-5 text-center">
                                    <div class="text-white opacity-10 mb-2"><i class="bi bi-database-exclamation display-3"></i></div>
                                    <h6 class="text-white opacity-50 fw-bold">Belum ada file backup kawan.</h6>
                                    <p class="text-white opacity-25 small italic">Sila buat backup pertama kawan kawan!</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fs-xs { font-size: 0.72rem; }
        .transform-hover:hover {
            transform: translateY(-2px);
        }
        .btn-animate:hover {
            box-shadow: 0 10px 20px rgba(255, 193, 7, 0.3);
        }
    </style>
</x-app-layout>
