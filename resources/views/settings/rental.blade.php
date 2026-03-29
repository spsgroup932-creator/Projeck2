<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-gear-wide-connected fs-4 text-primary"></i>
            <span class="outfit fw-bold">Pengaturan Rental & Bisnis</span>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <form action="{{ route('settings.rental.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row g-4">
                <!-- Left Column: Basic Info & Branding -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                            <h5 class="mb-0 outfit fw-bold"><i class="bi bi-info-circle me-2"></i> Profil & Identitas Bisnis</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Nama Rental / Perusahaan</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $branch->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Kode Rental (Unik)</label>
                                    <input type="text" class="form-control bg-dark opacity-75" value="{{ $branch->code }}" readonly disabled>
                                    <small class="text-warning opacity-75" style="font-size: 0.7rem;">* Kode rental tidak dapat diubah kawan.</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Alamat Lengkap Kantor</label>
                                    <textarea name="address" class="form-control" rows="3">{{ old('address', $branch->address) }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Nomor Telepon Kantor</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $branch->phone) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">WhatsApp (Aktif)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-success border-0 text-white"><i class="bi bi-whatsapp"></i></span>
                                        <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $branch->whatsapp_number) }}" placeholder="62812345678">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Alamat Email Bisnis</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $branch->email) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Website / Social Media</label>
                                    <input type="url" name="website" class="form-control" value="{{ old('website', $branch->website) }}" placeholder="https://www.rentalkawan.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Legal & Tax Section -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                            <h5 class="mb-0 outfit fw-bold"><i class="bi bi-shield-check me-2"></i> Legalitas & Administrasi</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Nomor Induk Berusaha (NIB)</label>
                                    <input type="text" name="nib" class="form-control" value="{{ old('nib', $branch->nib) }}" placeholder="Contoh: 1234567890123">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Nomor NPWP Perusahaan</label>
                                    <input type="text" name="npwp" class="form-control" value="{{ old('npwp', $branch->npwp) }}" placeholder="01.234.567.8-901.234">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Section -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                            <h5 class="mb-0 outfit fw-bold"><i class="bi bi-bank me-2"></i> Rekening Pembayaran (Muncul di Nota)</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Nama Bank</label>
                                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $branch->bank_name) }}" placeholder="Contoh: BCA / Mandiri">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Nomor Rekening</label>
                                    <input type="text" name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $branch->bank_account_number) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Nama Pemilik Rekening</label>
                                    <input type="text" name="bank_account_name" class="form-control" value="{{ old('bank_account_name', $branch->bank_account_name) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QRIS Payment Section -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                            <h5 class="mb-0 outfit fw-bold"><i class="bi bi-qr-code me-2"></i> QRIS Pembayaran</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            @if($branch->qris_image)
                                <div class="mb-3 glass-panel p-3 d-inline-block rounded-4">
                                    <img src="{{ asset('storage/' . $branch->qris_image) }}" alt="QRIS" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                                <p class="text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> QRIS sudah diupload kawan</p>
                            @else
                                <div class="mb-3 glass-panel p-4 rounded-4 text-muted">
                                    <i class="bi bi-qr-code fs-1 opacity-25"></i>
                                    <p class="small mb-0 mt-2">Belum ada QRIS kawan</p>
                                </div>
                            @endif
                            @if(auth()->user()->role === 'admin cabang' || auth()->user()->role === 'super admin')
                                <input type="file" name="qris_image" class="form-control form-control-sm mt-3" accept="image/*">
                                <small class="text-muted d-block mt-2">Upload gambar QRIS dari aplikasi bank kawan (Max 2MB).</small>
                            @else
                                <small class="text-warning d-block mt-2"><i class="bi bi-lock-fill me-1"></i>Hanya Admin yang dapat mengubah QRIS.</small>
                            @endif
                        </div>
                    </div>

                    <!-- Receipt T&C -->
                    <div class="card border-0 shadow-lg">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                            <h5 class="mb-0 outfit fw-bold"><i class="bi bi-file-earmark-text me-2"></i> Catatan & T&C Nota (Footer)</h5>
                        </div>
                        <div class="card-body p-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Syarat & Ketentuan Pembayaran</label>
                            <textarea name="receipt_footer" class="form-control" rows="5" placeholder="Contoh: Pembayaran dianggap sah jika sudah lunas... ">{{ old('receipt_footer', $branch->receipt_footer) }}</textarea>
                            <small class="text-muted mt-2 d-block">Tips: Teks ini akan muncul di bagian bawah setiap nota yang kawan cetak secara otomatis.</small>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Visual & Assets -->
                <div class="col-xl-4">
                    <!-- Logo Section -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                            <h5 class="mb-0 outfit fw-bold"><i class="bi bi-image me-2"></i> Logo Rental</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            @if($branch->logo)
                                <div class="mb-4 glass-panel p-3 d-inline-block rounded-4">
                                    <img src="{{ asset('storage/' . $branch->logo) }}" alt="Logo" class="img-fluid rounded" style="max-height: 120px;">
                                </div>
                            @else
                                <div class="mb-4 glass-panel p-4 rounded-4 text-muted">
                                    <i class="bi bi-image fs-1 opacity-25"></i>
                                    <p class="small mb-0 mt-2">Belum ada logo kawan</p>
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control form-control-sm mt-3" accept="image/*">
                            <small class="text-muted d-block mt-2">Gunakan file PNG transparan untuk hasil terbaik (Max 2MB).</small>
                        </div>
                    </div>

                    <!-- Watermark Section -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                            <h5 class="mb-0 outfit fw-bold"><i class="bi bi-droplet me-2"></i> Watermark Nota</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            @if($branch->watermark)
                                <div class="mb-4 glass-panel p-3 d-inline-block rounded-4 position-relative overflow-hidden" style="background: repeating-linear-gradient(45deg, rgba(255,255,255,0.05) 0, rgba(255,255,255,0.05) 10px, transparent 10px, transparent 20px);">
                                    <img src="{{ asset('storage/' . $branch->watermark) }}" alt="Watermark" class="img-fluid rounded opacity-50" style="max-height: 120px;">
                                    <div class="position-absolute top-50 start-50 translate-middle text-white fw-bold opacity-25 fs-5">PREVIEW</div>
                                </div>
                            @else
                                <div class="mb-4 glass-panel p-4 rounded-4 text-muted">
                                    <i class="bi bi-droplet-half fs-1 opacity-25"></i>
                                    <p class="small mb-0 mt-2">Belum ada watermark kawan</p>
                                </div>
                            @endif
                            <input type="file" name="watermark" class="form-control form-control-sm mt-3" accept="image/*">
                            <label class="form-label text-muted small fw-bold text-uppercase mt-4 d-block text-start">Atau Gunakan Teks Watermark</label>
                            <input type="text" name="watermark_text" class="form-control" value="{{ old('watermark_text', $branch->watermark_text) }}" placeholder="Lunas / Official">
                        </div>
                    </div>

                    <!-- Theme & Typography Section -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-header bg-transparent border-bottom border-white border-opacity-10 py-3">
                            <h5 class="mb-0 outfit fw-bold"><i class="bi bi-palette me-2"></i> Visual & Tipografi</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Warna Header & Tombol Utama</label>
                                <input type="color" name="header_color" class="form-control form-control-lg border-0 bg-transparent p-0" value="{{ old('header_color', $branch->header_color ?? '#6366f1') }}" style="height: 60px; cursor: pointer;">
                            </div>
                            
                            <div>
                                <label class="form-label text-muted small fw-bold text-uppercase">Pilih Gaya Font Dashboard</label>
                                <select name="font_family" class="form-select form-select-lg bg-dark border-secondary text-white">
                                    <option value="Inter" {{ (old('font_family', $branch->font_family) == 'Inter') ? 'selected' : '' }}>Inter (Default - Modern)</option>
                                    <option value="Outfit" {{ (old('font_family', $branch->font_family) == 'Outfit') ? 'selected' : '' }}>Outfit (Geometric & Bold)</option>
                                    <option value="Poppins" {{ (old('font_family', $branch->font_family) == 'Poppins') ? 'selected' : '' }}>Poppins (Friendly & Rounded)</option>
                                    <option value="Roboto" {{ (old('font_family', $branch->font_family) == 'Roboto') ? 'selected' : '' }}>Roboto (Clean & Professional)</option>
                                    <option value="Montserrat" {{ (old('font_family', $branch->font_family) == 'Montserrat') ? 'selected' : '' }}>Montserrat (Elegant & Classic)</option>
                                    <option value="Nunito" {{ (old('font_family', $branch->font_family) == 'Nunito') ? 'selected' : '' }}>Nunito (Soft & Readable)</option>
                                </select>
                                <small class="text-muted d-block mt-2">Semua font diambil otomatis dari Google Fonts kawan.</small>
                            </div>

                            <hr class="my-4 border-white border-opacity-10">

                            <div>
                                <label class="form-label text-warning small fw-bold text-uppercase"><i class="bi bi-person-gear"></i> Ukuran Font Pilihan Kawan (Hanya Untuk Anda)</label>
                                <select name="font_size" class="form-select bg-dark border-secondary text-white">
                                    <option value="12px" {{ auth()->user()->font_size == '12px' ? 'selected' : '' }}>Kecil (12px)</option>
                                    <option value="14px" {{ auth()->user()->font_size == '14px' ? 'selected' : '' }}>Sedang (14px)</option>
                                    <option value="16px" {{ (auth()->user()->font_size == '16px' || !auth()->user()->font_size) ? 'selected' : '' }}>Standar (16px)</option>
                                    <option value="18px" {{ auth()->user()->font_size == '18px' ? 'selected' : '' }}>Besar (18px)</option>
                                    <option value="20px" {{ auth()->user()->font_size == '20px' ? 'selected' : '' }}>Sangat Besar (20px)</option>
                                </select>
                                <small class="text-muted d-block mt-2">Kawan bisa atur ukuran tulisan yang paling pas di mata kawan sendiri.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Save Action -->
                    <div class="card border-0 shadow-lg bg-primary bg-gradient text-white overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-cloud-upload-fill fs-1 mb-3"></i>
                            <h4 class="fw-bold outfit">Simpan Perubahan?</h4>
                            <p class="small opacity-75">Pastikan semua data sudah benar sebelum diperbarui kawan.</p>
                            <button type="submit" class="btn btn-white w-100 rounded-pill py-3 fw-bold shadow mt-3">
                                <i class="bi bi-check2-circle me-2"></i> SIMPAN SEMUA PENGATURAN
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .btn-white {
            background: #fff;
            color: var(--primary);
            border: none;
        }
        .btn-white:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</x-app-layout>
