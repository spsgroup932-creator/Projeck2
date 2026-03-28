<x-app-layout>
    <x-slot name="header">Edit Pelanggan: {{ $customer->name }}</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-5">
                    <form action="{{ route('customers.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        
                        <div class="section-title mb-4 border-bottom border-white border-opacity-10 pb-2">
                            <h5 class="fw-bold text-white mb-1">Informasi Identitas</h5>
                            <p class="text-white opacity-50 small">Perbarui data profil pelanggan kawan di sini.</p>
                        </div>

                        <div class="row g-4 mb-5 pb-2">
                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">NIK (16 Digit) *</label>
                                <input type="text" name="nik" class="form-control glass-panel @error('nik') is-invalid @enderror" value="{{ old('nik', $customer->nik) }}">
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Nama Lengkap *</label>
                                <input type="text" name="name" class="form-control glass-panel @error('name') is-invalid @enderror" value="{{ old('name', $customer->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Nomor WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white bg-opacity-10 text-success border-0"><i class="bi bi-whatsapp"></i></span>
                                    <input type="text" name="phone" class="form-control glass-panel @error('phone') is-invalid @enderror" value="{{ old('phone', $customer->phone) }}">
                                </div>
                                @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Alamat / Lokasi</label>
                                <input type="text" name="address" class="form-control glass-panel @error('address') is-invalid @enderror" value="{{ old('address', $customer->address) }}">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="section-title mb-4 border-bottom border-white border-opacity-10 pb-2">
                            <h5 class="fw-bold text-white mb-1">Dokumen Lampiran</h5>
                            <p class="text-white opacity-50 small">Foto KTP digunakan untuk validasi member kawan.</p>
                        </div>

                        <div class="row g-4 d-flex align-items-center">
                            <div class="col-md-4">
                                <div class="p-2 border border-white border-opacity-10 rounded-3 bg-white bg-opacity-5 text-center">
                                    @if($customer->ktp_photo)
                                        <img src="{{ asset('storage/'.$customer->ktp_photo) }}" class="img-fluid rounded shadow-sm" style="max-height: 120px;">
                                    @else
                                        <div class="py-4 text-white opacity-25"><i class="bi bi-image fs-1 d-block mb-1"></i><span class="small italic">No Photo</span></div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label text-white fw-medium">Ganti Foto KTP (Optional)</label>
                                <input type="file" name="ktp_photo" class="form-control glass-panel @error('ktp_photo') is-invalid @enderror">
                                <div class="form-text mt-2 small text-white opacity-50 italic">Biarkan kosong bila tidak ingin mengganti foto yang sudah ada kawan.</div>
                                @error('ktp_photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-5 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm fw-bold">
                                <i class="bi bi-save2-fill me-2"></i> Update Pelanggan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
