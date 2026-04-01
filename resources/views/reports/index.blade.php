<x-app-layout>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-white mb-1 outfit">Laporan Operasional & Finansial</h2>
                <p class="text-white opacity-50 mb-0">Pantau performa bisnis kawan dalam satu dashboard terpadu.</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-light border-white border-opacity-10 bg-white bg-opacity-5">
                    <i class="bi bi-printer me-2"></i>Cetak Laporan
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card border-0 shadow-lg p-4 mb-4" style="background: rgba(30, 41, 59, 0.5); backdrop-filter: blur(10px);">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-white opacity-75 small fw-bold text-uppercase">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control bg-dark border-white border-opacity-10 text-white" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-white opacity-75 small fw-bold text-uppercase">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control bg-dark border-white border-opacity-10 text-white" value="{{ $endDate }}">
                </div>
                @if($isSuperAdmin)
                <div class="col-md-3">
                    <label class="form-label text-white opacity-75 small fw-bold text-uppercase">Cabang</label>
                    <select name="branch_id" class="form-select bg-dark border-white border-opacity-10 text-white">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary shadow-lg">
                        <i class="bi bi-filter me-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- KPI Cards -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="text-white opacity-75 small fw-bold text-uppercase mb-1 outfit">Total Pendapatan</div>
                    <div class="h3 mb-0 fw-bold text-white outfit">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    <div class="mt-2 small text-white opacity-50">Berdasarkan pembayaran diterima</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                    <div class="text-white opacity-75 small fw-bold text-uppercase mb-1 outfit">Biaya Perawatan</div>
                    <div class="h3 mb-0 fw-bold text-white outfit">Rp {{ number_format($totalMaintenance, 0, ',', '.') }}</div>
                    <div class="mt-2 small text-white opacity-50">Total biaya maintenance armada</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                    <div class="text-white opacity-75 small fw-bold text-uppercase mb-1 outfit">Total Job Order</div>
                    <div class="h3 mb-0 fw-bold text-white outfit">{{ $totalOrders }} <small class="fs-6 opacity-50">SPJ</small></div>
                    <div class="mt-2 small text-white opacity-50">{{ $closedOrders }} Berhasil diselesaikan</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="text-white opacity-75 small fw-bold text-uppercase mb-1 outfit">Estimasi Margin</div>
                    <div class="h3 mb-0 fw-bold text-white outfit">Rp {{ number_format($totalRevenue - $totalMaintenance, 0, ',', '.') }}</div>
                    <div class="mt-2 small text-white opacity-50">Pendapatan - Maintenance</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4 mb-4">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg p-4">
                    <h5 class="fw-bold mb-4 text-white outfit">Trend Finansial (6 Bulan Terakhir)</h5>
                    <div style="height: 400px;">
                        <canvas id="financialTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('financialTrendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Pendapatan (Rp)',
                            data: {!! json_encode($revenueData) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointBackgroundColor: '#10b981',
                            borderWidth: 3
                        },
                        {
                            label: 'Maintenance (Rp)',
                            data: {!! json_encode($maintenanceData) !!},
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 6,
                            pointBackgroundColor: '#ef4444',
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { 
                                color: 'rgba(255, 255, 255, 0.5)',
                                callback: value => 'Rp ' + value.toLocaleString()
                            }
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: 'rgba(255, 255, 255, 0.5)' } 
                        }
                    },
                    plugins: {
                        legend: { 
                            position: 'top',
                            labels: { color: '#fff', font: { family: 'Outfit', size: 12 } } 
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Outfit' },
                            bodyFont: { family: 'Outfit' },
                            padding: 12,
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
