<x-app-layout>
    <div class="row g-4 mb-4">
        @if($isSuperAdmin)
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="d-flex align-items-center gap-4">
                    <div class="sidebar-logo bg-white bg-opacity-20 text-white" style="width: 56px; height: 56px;">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                    <div>
                        <div class="text-white opacity-75 small fw-bold text-uppercase mb-1 outfit" style="letter-spacing: 1px;">Cuan Langganan</div>
                        <div class="h4 mb-0 fw-bold text-white outfit">Rp {{ number_format($stats['total_subscription_revenue'], 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition">
                <div class="d-flex align-items-center gap-4">
                    <div class="sidebar-logo" style="width: 56px; height: 56px; background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                    <div>
                        <div class="text-white opacity-50 small fw-bold text-uppercase mb-1 outfit" style="letter-spacing: 1px;">Staff Cabang</div>
                        <div class="h4 mb-0 fw-bold text-white outfit">{{ number_format($stats['total_staff']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition">
                <div class="d-flex align-items-center gap-4">
                    <div class="sidebar-logo" style="width: 56px; height: 56px; background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="bi bi-person-badge-fill fs-3"></i>
                    </div>
                    <div>
                        <div class="text-white opacity-50 small fw-bold text-uppercase mb-1 outfit" style="letter-spacing: 1px;">Pelanggan</div>
                        <div class="h4 mb-0 fw-bold text-white outfit">{{ number_format($stats['total_customers']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition">
                <div class="d-flex align-items-center gap-4">
                    <div class="sidebar-logo" style="width: 56px; height: 56px; background: rgba(6, 182, 212, 0.1); color: #06b6d4;">
                        <i class="bi bi-truck fs-3"></i>
                    </div>
                    <div>
                        <div class="text-white opacity-50 small fw-bold text-uppercase mb-1 outfit" style="letter-spacing: 1px;">Unit Armada</div>
                        <div class="h4 mb-0 fw-bold text-white outfit">{{ number_format($stats['total_units']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition">
                <div class="d-flex align-items-center gap-4">
                    <div class="sidebar-logo" style="width: 56px; height: 56px; background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="bi bi-activity fs-3"></i>
                    </div>
                    <div>
                        <div class="text-white opacity-50 small fw-bold text-uppercase mb-1 outfit" style="letter-spacing: 1px;">SPJ Aktif</div>
                        <div class="h4 mb-0 fw-bold text-white outfit">{{ number_format($stats['active_orders']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- View untuk Admin Cabang (Simplified kawan) -->
        <div class="col-12 col-md-6">
            <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <div class="d-flex align-items-center gap-4">
                    <div class="sidebar-logo bg-white bg-opacity-20 text-white" style="width: 72px; height: 72px;">
                        <i class="bi bi-truck fs-1"></i>
                    </div>
                    <div>
                        <div class="text-white opacity-75 small fw-bold text-uppercase mb-1 outfit" style="letter-spacing: 1px;">Unit Yang Jalan kawan</div>
                        <div class="h1 mb-0 fw-bold text-white outfit">{{ number_format($stats['units_on_road']) }} <small class="fs-5 opacity-50">Unit</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card h-100 p-4 border-0 shadow-lg hover-lift transition">
                <div class="d-flex align-items-center gap-4">
                    <div class="sidebar-logo" style="width: 72px; height: 72px; background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="bi bi-activity fs-1"></i>
                    </div>
                    <div>
                        <div class="text-white opacity-50 small fw-bold text-uppercase mb-1 outfit" style="letter-spacing: 1px;">Total SPJ Aktif</div>
                        <div class="h1 mb-0 fw-bold text-white outfit">{{ number_format($stats['active_orders']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Revenue & Maintenance Graph Section -->
    <div class="row g-4 mb-4">
        @if($showFinChart)
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0 text-white outfit">Performa Finansial</h5>
                        <small class="text-white opacity-50">Perbandingan Pendapatan vs Biaya Maintenance kawan.</small>
                    </div>
                </div>
                <div style="height: 350px;">
                    <canvas id="revenueMaintenanceChart"></canvas>
                </div>
            </div>
        </div>
        @endif
        @if($showUtilChart)
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg p-4 h-100 text-center">
                <h5 class="fw-bold mb-4 text-white outfit">Utilitas Armada</h5>
                <div style="height: 250px;" class="mb-4">
                    <canvas id="utilizationChart"></canvas>
                </div>
                <div class="d-flex justify-content-around mt-auto">
                    <div>
                        <div class="small text-white opacity-50">Sedang Jalan</div>
                        <div class="h5 mb-0 text-primary fw-bold">{{ $activeUnitsCount }}</div>
                    </div>
                    <div class="vr bg-white opacity-10"></div>
                    <div>
                        <div class="small text-white opacity-50">Standby/Idle</div>
                        <div class="h5 mb-0 text-secondary fw-bold">{{ $idleUnitsCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Top Customers & Announcements -->
    <div class="row g-4 mb-4">
        @if($showTopCustomers)
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg p-4 h-100">
                <h5 class="fw-bold mb-4 text-white outfit">Pelanggan Teraktif kawan</h5>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0">
                        <thead class="text-white opacity-50 small text-uppercase">
                            <tr>
                                <th class="border-0 ps-0">Nama Pelanggan</th>
                                <th class="border-0">Total Job Order</th>
                                <th class="border-0">Perusahaan</th>
                                <th class="border-0 text-end pe-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-white border-0">
                            @foreach($topCustomers as $customer)
                            <tr>
                                <td class="border-0 ps-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="sidebar-logo rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 32px; height: 32px; min-width: 32px;">
                                            {{ substr($customer->name, 0, 1) }}
                                        </div>
                                        <span class="fw-bold">{{ $customer->name }}</span>
                                    </div>
                                </td>
                                <td class="border-0">{{ $customer->job_orders_count }}</td>
                                <td class="border-0 text-white opacity-75">{{ $customer->company_name ?? '-' }}</td>
                                <td class="border-0 text-end pe-0">
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg overflow-hidden h-100 position-relative" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <div class="position-absolute top-0 end-0 p-4 opacity-10 pointer-events-none">
                    <i class="bi bi-rocket-takeoff text-primary" style="font-size: 5rem;"></i>
                </div>
                <div class="card-body p-4 position-relative d-flex flex-column justify-content-center">
                    <h4 class="fw-bold text-white mb-3 outfit">Halo, {{ auth()->user()->name }}!</h4>
                    <p class="text-white opacity-75 mb-4 outfit small">
                        Selamat datang di pusat kendali operasional rental kawan. Kelola armada dan mantau performa dengan lebih mudah.
                        @if(!$isSuperAdmin)
                            <br><span class="text-primary fw-bold">Cabang: {{ auth()->user()->branch->name ?? 'Pusat' }}</span>
                        @endif
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('job-orders.create') }}" class="btn btn-primary shadow-lg py-2">
                            <i class="bi bi-plus-lg me-1"></i> Buat SPJ Baru
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-light py-2 border-white border-opacity-10 bg-white bg-opacity-5">
                            <i class="bi bi-file-earmark-bar-graph me-1"></i> Laporan Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue & Maintenance Chart
            const canvasFin = document.getElementById('revenueMaintenanceChart');
            if (canvasFin) {
                const ctxFin = canvasFin.getContext('2d');
                new Chart(ctxFin, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($labels) !!},
                        datasets: [
                            {
                                label: 'Pendapatan (Rp)',
                                data: {!! json_encode($revenueData) !!},
                                backgroundColor: '#3b82f6',
                                borderRadius: 8,
                            },
                            {
                                label: 'Maintenance (Rp)',
                                data: {!! json_encode($maintenanceData) !!},
                                backgroundColor: '#ef4444',
                                borderRadius: 8,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                ticks: { 
                                    color: 'rgba(255, 255, 255, 0.5)',
                                    callback: value => 'Rp ' + value.toLocaleString()
                                }
                            },
                            x: { grid: { display: false }, ticks: { color: 'rgba(255, 255, 255, 0.5)' } }
                        },
                        plugins: {
                            legend: { labels: { color: '#fff', font: { family: 'Outfit' } } }
                        }
                    }
                });
            }

            // Utilization Chart
            const canvasUtil = document.getElementById('utilizationChart');
            if (canvasUtil) {
                const ctxUtil = canvasUtil.getContext('2d');
                new Chart(ctxUtil, {
                    type: 'doughnut',
                    data: {
                        labels: ['Sedang Jalan', 'Standby'],
                        datasets: [{
                            data: [{{ $activeUnitsCount }}, {{ $idleUnitsCount }}],
                            backgroundColor: ['#3b82f6', 'rgba(255, 255, 255, 0.1)'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
