<x-app-layout>
    <div class="mb-4">
        <h4 class="fw-bold text-white mb-0 outfit">Edit Log Maintenance kawan</h4>
        <p class="text-white opacity-50 mb-0 small">Perbarui data servis armada.</p>
    </div>

    <div class="card border-0 shadow-lg p-4">
        <form action="{{ route('maintenance.update', $maintenance->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-white opacity-50 small fw-bold text-uppercase">Pilih Unit Armada</label>
                    <select name="unit_id" class="form-select bg-dark border-secondary text-white py-2" required>
                        <option value="">-- Pilih Unit kawan --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $maintenance->unit_id == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nopol }} - {{ $unit->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white opacity-50 small fw-bold text-uppercase">Tanggal Servis</label>
                    <input type="date" name="service_date" value="{{ $maintenance->service_date }}" class="form-control bg-dark border-secondary text-white py-2" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white opacity-50 small fw-bold text-uppercase">Biaya Service (IDR)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-white">Rp</span>
                        <input type="number" name="cost" value="{{ $maintenance->cost }}" class="form-control bg-dark border-secondary text-white py-2" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white opacity-50 small fw-bold text-uppercase">Kilometer Saat Ini</label>
                    <input type="number" name="current_mileage" value="{{ $maintenance->current_mileage }}" class="form-control bg-dark border-secondary text-white py-2">
                </div>

                <div class="col-md-12">
                    <label class="form-label text-white opacity-50 small fw-bold text-uppercase">Nama Mekanik / Bengkel</label>
                    <input type="text" name="mechanic_name" value="{{ $maintenance->mechanic_name }}" class="form-control bg-dark border-secondary text-white py-2">
                </div>

                <div class="col-md-12">
                    <label class="form-label text-white opacity-50 small fw-bold text-uppercase">Deskripsi Pekerjaan</label>
                    <textarea name="description" class="form-control bg-dark border-secondary text-white py-2" rows="4" required>{{ $maintenance->description }}</textarea>
                </div>

                <div class="col-12 mt-4 pt-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-lg">
                            <i class="bi bi-save me-2"></i> Update Log
                        </button>
                        <a href="{{ route('maintenance.index') }}" class="btn btn-outline-light px-4 py-2 rounded-pill border-white border-opacity-10 bg-white bg-opacity-5">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @push('js')
    <script>
        $(document).ready(function() {
            $('select[name="unit_id"]').select2({
                width: '100%'
            });
        });
    </script>
    @endpush
</x-app-layout>
