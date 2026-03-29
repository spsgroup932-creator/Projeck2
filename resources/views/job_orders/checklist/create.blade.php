<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-clipboard-check-fill fs-4 text-{{ $type === 'departure' ? 'primary' : 'success' }}"></i>
            <span class="outfit fw-bold">Checklist Unit: {{ $type === 'departure' ? 'Keberangkatan' : 'Kepulangan' }}</span>
        </div>
    </x-slot>

    <div class="container-fluid py-4 font-sans text-white">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <form action="{{ route('unit-checklists.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="job_order_id" value="{{ $jobOrder->id }}">
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div class="row g-4">
                        {{-- Ringkasan JO --}}
                        <div class="col-12">
                            <div class="card glass-panel border-0 shadow-lg p-4 mb-2">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="text-white opacity-50 small mb-1 uppercase tracking-wider fs-xs">No SPJ kawan</div>
                                        <div class="h5 mb-0 fw-bold outfit text-primary">{{ $jobOrder->spj_number }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-white opacity-50 small mb-1 uppercase tracking-wider fs-xs">Unit & Nopol</div>
                                        <div class="fw-bold">{{ $jobOrder->unit->name }} ({{ $jobOrder->unit->nopol }})</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-white opacity-50 small mb-1 uppercase tracking-wider fs-xs">Driver</div>
                                        <div class="fw-bold text-info">{{ $jobOrder->driver->name }}</div>
                                    </div>
                                    <div class="col-md-3 text-md-end">
                                        <div class="badge bg-{{ $type === 'departure' ? 'primary' : 'success' }} bg-opacity-20 text-{{ $type === 'departure' ? 'primary' : 'success' }} px-3 py-2 border border-{{ $type === 'departure' ? 'primary' : 'success' }} border-opacity-25 uppercase fs-xs fw-bold">
                                            Status: {{ $type === 'departure' ? 'Keluar' : 'Masuk' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Input Utama --}}
                        <div class="col-md-4">
                            <div class="card glass-panel border-0 shadow-lg h-100 p-4">
                                <h6 class="fw-bold outfit mb-4 text-white"><i class="bi bi-speedometer2 text-warning me-2"></i>Data Operasional</h6>
                                
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Kilometer Unit (KM)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-secondary text-white"><i class="bi bi-hash"></i></span>
                                        <input type="number" name="km_reading" class="form-control" placeholder="Input KM saat ini..." required>
                                    </div>
                                    <small class="text-info opacity-75 mt-1 d-block italic">KM terakhir: {{ $jobOrder->unit->km_last ?? 'N/A' }}</small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Bahan Bakar (BBM)</label>
                                    <select name="fuel_level" class="form-select bg-dark border-secondary text-white" required>
                                        <option value="">-- Pilih Level BBM --</option>
                                        <option value="E">Empty (E)</option>
                                        <option value="1/4">1/4 Bar</option>
                                        <option value="1/2">1/2 Bar</option>
                                        <option value="3/4">3/4 Bar</option>
                                        <option value="F">Full (F)</option>
                                    </select>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label text-muted small fw-bold text-uppercase">Tanggal & Waktu Cek</label>
                                    <input type="datetime-local" name="check_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Checklist Item Fisik --}}
                        <div class="col-md-8">
                            <div class="card glass-panel border-0 shadow-lg h-100 p-4">
                                <h6 class="fw-bold outfit mb-4 text-white"><i class="bi bi-shield-check text-success me-2"></i>Pemeriksaan Fisik & Dokumen</h6>
                                
                                <div class="row">
                                    <div class="col-md-6 border-end border-white border-opacity-10">
                                        <p class="small text-uppercase fw-bold text-primary mb-3">Kondisi Kendaraan</p>
                                        @php
                                            $items = [
                                                'body' => 'Bodi (Lecet/Penyok)',
                                                'ban' => 'Kondisi Ban',
                                                'oli' => 'Cek Oli Mesin',
                                                'radiator' => 'Air Radiator',
                                                'lampu' => 'Lampu-lampu',
                                                'toolkit' => 'Kunci Roda/Dongkrak'
                                            ];
                                        @endphp
                                        @foreach($items as $key => $label)
                                        <div class="form-check mb-2 py-1">
                                            <input class="form-check-input" type="checkbox" name="items[]" value="{{ $key }}" id="item_{{ $key }}" checked>
                                            <label class="form-check-label text-white opacity-75" for="item_{{ $key }}">{{ $label }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-6 ps-md-4">
                                        <p class="small text-uppercase fw-bold text-success mb-3">Kelengkapan Dokumen</p>
                                        @php
                                            $docs = [
                                                'stnk' => 'STNK Asli / Fotokopi',
                                                'surat_jalan' => 'Surat Jalan / SPJ',
                                                'ktp' => 'Identitas Driver',
                                                'kir' => 'Buku KIR / Pajak Jalan'
                                            ];
                                        @endphp
                                        @foreach($docs as $key => $label)
                                        <div class="form-check mb-2 py-1">
                                            <input class="form-check-input text-success" type="checkbox" name="documents[]" value="{{ $key }}" id="doc_{{ $key }}" checked>
                                            <label class="form-check-label text-white opacity-75" for="doc_{{ $key }}">{{ $label }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Foto & Catatan --}}
                        <div class="col-12">
                            <div class="card glass-panel border-0 shadow-lg p-4 mb-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold outfit mb-3 text-white"><i class="bi bi-camera text-info me-2"></i>Bukti Foto (Opsional)</h6>
                                        <div class="input-group">
                                            <input type="file" name="photo_files[]" class="form-control bg-dark border-secondary text-white" multiple accept="image/*">
                                        </div>
                                        <small class="text-white opacity-25 mt-1 d-block italic">Kawan bisa pilih banyak foto sekaligus kawan.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold outfit mb-3 text-white"><i class="bi bi-sticky text-secondary me-2"></i>Catatan Tambahan</h6>
                                        <textarea name="notes" class="form-control bg-dark border-secondary text-white" rows="2" placeholder="Tulis catatan jika ada kendala di unit kawan..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="col-12 text-end">
                            <hr class="border-white border-opacity-10 mb-4">
                            <button type="button" class="btn btn-outline-light px-4 me-2 border-0" onclick="history.back()">Batal</button>
                            <button type="submit" class="btn btn-{{ $type === 'departure' ? 'primary' : 'success' }} px-5 py-2 fw-bold shadow-lg">
                                <i class="bi bi-check2-circle me-1"></i> SIMPAN CHECKLIST SEKARANG!
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .fs-xs { font-size: 0.7rem; }
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
    </style>
</x-app-layout>
