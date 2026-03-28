<x-app-layout>
    <x-slot name="header">Edit Unit: {{ $unit->unit_code }}</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('units.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5 text-dark">
                    <form action="{{ route('units.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        
                        <div class="section-title mb-4 border-bottom border-white border-opacity-10 pb-2">
                            <h5 class="fw-bold text-white mb-1">Perbarui Informasi Unit</h5>
                            <p class="text-white opacity-50 small">Update data kendaraan operasional kawan kawan.</p>
                        </div>

                        <div class="row g-4 border-bottom border-white border-opacity-10 pb-5 mb-5">
                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Nama Unit / Merk</label>
                                <input type="text" name="name" class="form-control glass-panel @error('name') is-invalid @enderror" value="{{ old('name', $unit->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Nomor Polisi</label>
                                <input type="text" name="nopol" class="form-control glass-panel @error('nopol') is-invalid @enderror text-uppercase fw-bold" value="{{ old('nopol', $unit->nopol) }}">
                                @error('nopol') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Tahun</label>
                                <input type="number" name="year" class="form-control glass-panel @error('year') is-invalid @enderror" value="{{ old('year', $unit->year) }}">
                                @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Nomor Rangka</label>
                                <input type="text" name="chassis_number" class="form-control glass-panel @error('chassis_number') is-invalid @enderror font-monospace" value="{{ old('chassis_number', $unit->chassis_number) }}">
                                @error('chassis_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <h6 class="text-white opacity-75 small fw-bold text-uppercase mt-3 mb-3 border-top border-white border-opacity-10 pt-4">Dokumen Kendaraan</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Masa Berlaku STNK</label>
                                <input type="date" name="stnk_expiry" class="form-control glass-panel @error('stnk_expiry') is-invalid @enderror" value="{{ old('stnk_expiry', $unit->stnk_expiry) }}">
                                @error('stnk_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Masa Berlaku KIR</label>
                                <input type="date" name="kir_expiry" class="form-control glass-panel @error('kir_expiry') is-invalid @enderror" value="{{ old('kir_expiry', $unit->kir_expiry) }}">
                                @error('kir_expiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Update Dokumen (PDF/Scan)</label>
                                <input type="file" name="document" class="form-control glass-panel @error('document') is-invalid @enderror">
                                @if($unit->document_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($unit->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-3 rounded-pill">
                                            <i class="bi bi-file-earmark-check me-1"></i> Lihat Dokumen Saat Ini
                                        </a>
                                    </div>
                                @endif
                                @error('document') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary px-5 shadow-sm fw-bold">
                                <i class="bi bi-save2 me-2"></i> Update Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
