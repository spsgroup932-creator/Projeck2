<x-app-layout>
    <x-slot name="header">Edit Driver: {{ $driver->name }}</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('drivers.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <div class="row justify-content-center py-4">
        <div class="col-md-10 col-lg-8">
            <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                <div class="card-header bg-primary p-4 border-0">
                    <h5 class="mb-0 text-white fw-bold"><i class="bi bi-pencil-square me-2"></i> Perbarui Profile Driver</h5>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('drivers.update', $driver->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-white">Nama Lengkap</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white bg-opacity-10 border-0"><i class="bi bi-person text-primary"></i></span>
                                    <input type="text" name="name" class="form-control glass-panel border-start-0 @error('name') is-invalid @enderror" value="{{ old('name', $driver->name) }}">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-white">Umur</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white bg-opacity-10 border-0"><i class="bi bi-calendar-event text-primary"></i></span>
                                    <input type="number" name="age" class="form-control glass-panel border-start-0 @error('age') is-invalid @enderror" value="{{ old('age', $driver->age) }}">
                                    <span class="input-group-text bg-white bg-opacity-10 border-0 text-white opacity-50 small">Tahun</span>
                                    @error('age') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-white">Update Foto KTP</label>
                                <div class="input-group input-group-lg">
                                    <input type="file" name="ktp_photo" class="form-control glass-panel @error('ktp_photo') is-invalid @enderror">
                                    @error('ktp_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-text mt-1 text-white opacity-50 small">Biarkan kosong jika tidak diganti kawan.</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-white">Alamat Domisili</label>
                                <textarea name="address" rows="4" class="form-control glass-panel @error('address') is-invalid @enderror">{{ old('address', $driver->address) }}</textarea>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-primary py-3 fw-bold">
                                <i class="bi bi-check2-circle me-2"></i> Update Profile Driver
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
