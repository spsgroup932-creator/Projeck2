<x-app-layout>
    <x-slot name="header">Registrasi Pelanggan Baru</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-body p-5">
                    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-20 text-danger mb-4 rounded-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li class="small">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-white mb-1">Data Diri Pelanggan</h5>
                            <p class="text-white opacity-50 small">Informasi dasar pelanggan yang akan terdaftar kawan.</p>
                        </div>

                        <div class="row g-4 border-bottom border-white border-opacity-10 pb-5 mb-5">
                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">NIK (16 Digit) *</label>
                                <input type="text" name="nik" class="form-control glass-panel @error('nik') is-invalid @enderror" value="{{ old('nik') }}" placeholder="Contoh: 3312345678901234" required maxlength="16" minlength="16">
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Nama Lengkap *</label>
                                <input type="text" name="name" class="form-control glass-panel @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Nomor WhatsApp / HP (Opsional)</label>
                                <input type="text" name="phone" class="form-control glass-panel @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Alamat Domisili (Opsional)</label>
                                <input type="text" name="address" class="form-control glass-panel @error('address') is-invalid @enderror" value="{{ old('address') }}">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-white mb-1">Verifikasi Identitas</h5>
                            <p class="text-white opacity-50 small">Upload Scan/Foto KTP asli untuk verifikasi data kawan.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Unggah Foto KTP</label>
                                <div class="upload-area p-4 border border-dashed border-white border-opacity-10 rounded-3 text-center bg-white bg-opacity-5">
                                    <i class="bi bi-cloud-arrow-up fs-2 text-primary d-block mb-2"></i>
                                    <input type="file" name="ktp_photo" class="form-control glass-panel @error('ktp_photo') is-invalid @enderror mt-2">
                                    @error('ktp_photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="bi bi-check-lg me-2"></i> Daftarkan Pelanggan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
