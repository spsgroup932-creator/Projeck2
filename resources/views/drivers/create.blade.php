<x-app-layout>
    <x-slot name="header">Tambah Driver Baru</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('drivers.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-body p-5">
                    <form action="{{ route('drivers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-white mb-1">Informasi Personal</h5>
                            <p class="text-white opacity-50 small">Lengkapi data diri driver sesuai dengan KTP asli kawan.</p>
                        </div>

                        <div class="row g-4 border-bottom pb-5 mb-5">
                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Nama Lengkap Driver</label>
                                <input type="text" name="name" class="form-control glass-panel @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Muhammad Budi">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Umur (Tahun)</label>
                                <input type="number" name="age" class="form-control glass-panel @error('age') is-invalid @enderror" value="{{ old('age') }}" placeholder="Masukkan usia">
                                @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Alamat Sesuai KTP</label>
                                <input type="text" name="address" class="form-control glass-panel @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Jl. Raya Utama No. 123">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-white mb-1">Dokumen & Persyaratan</h5>
                            <p class="text-white opacity-50 small">Pastikan foto KTP terlihat jelas dan tidak buram kawan.</p>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Unggah Foto KTP</label>
                                <div class="upload-area p-4 border border-dashed border-white border-opacity-10 rounded-3 text-center bg-white bg-opacity-5 position-relative">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-primary mb-2 d-block"></i>
                                    <span class="text-white opacity-50 d-block small mb-3">Tarik file ke sini atau klik untuk memilih kawan</span>
                                    <input type="file" name="ktp_photo" class="form-control glass-panel @error('ktp_photo') is-invalid @enderror">
                                    @error('ktp_photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light px-4">Reset Form</button>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="bi bi-save2-fill me-2"></i> Daftarkan Driver
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 bg-soft-primary mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-white mb-3"><i class="bi bi-info-circle me-2 text-warning"></i> Petunjuk Pengisian</h6>
                    <ul class="list-unstyled small mb-0 d-grid gap-2 text-white">
                        <li><i class="bi bi-check2-circle me-2 text-primary"></i> Gunakan Nama Lengkap tanpa gelar.</li>
                        <li><i class="bi bi-check2-circle me-2 text-primary"></i> Umur disesuaikan dengan tanggal lahir di KTP.</li>
                        <li><i class="bi bi-check2-circle me-2 text-primary"></i> Lampirkan file KTP berformat JPG atau PNG.</li>
                    </ul>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <div class="bg-white bg-opacity-5 rounded-4 py-5 mb-3 border border-dashed border-white border-opacity-10">
                        <i class="bi bi-person-video fs-1 text-white opacity-25"></i>
                    </div>
                    <p class="text-white opacity-50 small mb-0">Preview Foto KTP akan muncul di sini setelah diunggah kawan.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: rgba(255,255,255,0.1) !important; }
    .section-title h5 { color: #fff; letter-spacing: -0.01em; }
    .bg-soft-primary { background-color: rgba(99, 102, 241, 0.1) !important; border: 1px solid rgba(99, 102, 241, 0.2) !important; }
</style>
