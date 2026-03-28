<x-app-layout>
    <x-slot name="header">Tambah Unit Baru</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('units.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0">
                <div class="card-body p-5 text-dark">
                    <form action="{{ route('units.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="section-title mb-4">
                            <h5 class="fw-bold text-white mb-1">Informasi Kendaraan</h5>
                            <p class="text-white opacity-50 small">Masukkan detail unit operasional yang akan didaftarkan kawan.</p>
                        </div>

                        <div class="row g-4 border-bottom border-white border-opacity-10 pb-5 mb-5">
                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Nama / Merk Unit</label>
                                <input type="text" name="name" class="form-control glass-panel @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Hino Dutro 130 HD">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Nomor Polisi (Plat)</label>
                                <input type="text" name="nopol" class="form-control glass-panel @error('nopol') is-invalid @enderror text-uppercase fw-bold" value="{{ old('nopol') }}" placeholder="L 1234 AB">
                                @error('nopol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Tahun Kendaraan</label>
                                <input type="number" name="year" class="form-control glass-panel @error('year') is-invalid @enderror" value="{{ old('year') }}" placeholder="2023">
                                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Nomor Rangka</label>
                                <input type="text" name="chassis_number" class="form-control glass-panel @error('chassis_number') is-invalid @enderror font-monospace" value="{{ old('chassis_number') }}" placeholder="MHF123...">
                                @error('chassis_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <h6 class="text-white opacity-75 small fw-bold text-uppercase mt-3 mb-3 border-top border-white border-opacity-10 pt-4">Dokumen Kendaraan</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Masa Berlaku STNK</label>
                                <input type="date" name="stnk_expiry" class="form-control glass-panel @error('stnk_expiry') is-invalid @enderror" value="{{ old('stnk_expiry') }}">
                                @error('stnk_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Masa Berlaku KIR</label>
                                <input type="date" name="kir_expiry" class="form-control glass-panel @error('kir_expiry') is-invalid @enderror" value="{{ old('kir_expiry') }}">
                                @error('kir_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Upload Dokumen (PDF/Scan)</label>
                                <input type="file" name="document" class="form-control glass-panel @error('document') is-invalid @enderror">
                                <small class="text-white opacity-50">Upload scan STNK & KIR dalam satu PDF atau Gambar kawan.</small>
                                @error('document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="bi bi-save2-fill me-2"></i> Simpan Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 bg-white bg-opacity-5 mb-4 border border-white border-opacity-10">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-white mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> Data Teknis</h6>
                    <p class="small text-white opacity-50 mb-0">Pastikan Nomor Rangka sesuai dengan STNK asli kendaraan untuk menghindari kesalahan administrasi kawan.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
