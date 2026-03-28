<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2 text-dark">
            <i class="bi bi-person-vcard fs-4 text-primary"></i>
            <span>Profil Pelanggan: {{ $customer->name }}</span>
        </div>
    </x-slot>
    <x-slot name="header_actions">
        <div class="d-flex gap-2">
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square fs-5"></i> Edit Profil
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-light px-4 py-2 border shadow-sm rounded-3">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="container-fluid py-4 text-dark">
        <div class="row g-4">
            <!-- Left Column: Profile Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
                    <div class="card-body">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto shadow mb-4" 
                             style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 48px;">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <h4 class="fw-bold mb-1">{{ $customer->name }}</h4>
                        <div class="badge bg-light text-dark px-3 py-2 rounded-pill border mb-4">
                            ID: {{ $customer->customer_code }}
                        </div>
                        
                        <div class="border-top pt-4 text-start">
                            <div class="mb-4">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Status Keanggotaan</label>
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Aktif kawan
                                </span>
                            </div>
                            <div class="mb-4">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Terdaftar Sejak</label>
                                <div class="fw-bold"><i class="bi bi-calendar-event me-2 text-primary"></i>{{ $customer->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & Documents -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-info-circle-fill me-2 text-primary"></i> Data Lengkap Pelanggan</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Nomor NIK</label>
                                <div class="p-3 bg-light rounded-3 fw-bold">{{ $customer->nik }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Nomor WhatsApp</label>
                                <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">{{ $customer->phone }}</span>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customer->phone) }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                                        <i class="bi bi-whatsapp me-1"></i> Chat
                                    </a>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Alamat Tinggal</label>
                                <div class="p-3 bg-light rounded-3">{{ $customer->address }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-image me-2 text-primary"></i> Foto Kartu Identitas (KTP)</h6>
                    </div>
                    <div class="card-body p-4 text-center">
                        @if($customer->ktp_photo)
                            <div class="p-3 border rounded-4 bg-light shadow-inner">
                                <img src="{{ asset('storage/'.$customer->ktp_photo) }}" class="img-fluid rounded-3 shadow" style="max-height: 400px;">
                            </div>
                            <a href="{{ asset('storage/'.$customer->ktp_photo) }}" target="_blank" class="btn btn-link text-primary mt-3 text-decoration-none fw-bold">
                                <i class="bi bi-fullscreen me-2"></i> Lihat Ukuran Penuh
                            </a>
                        @else
                            <div class="py-5 text-muted border border-dashed rounded-4">
                                <i class="bi bi-camera-slash fs-1 d-block mb-2"></i>
                                <span class="fw-medium">Belum ada foto KTP yang diunggah kawan.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
