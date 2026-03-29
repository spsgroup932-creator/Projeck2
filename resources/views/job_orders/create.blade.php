<x-app-layout>
    <x-slot name="header">Buat Job Order (SPJ) Baru</x-slot>
    <x-slot name="header_actions">
        <a href="{{ route('job-orders.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 border border-secondary border-opacity-50 text-white">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </x-slot>

    <form action="{{ route('job-orders.store') }}" method="POST" id="spjForm">
        @csrf
        
        @if ($errors->any())
            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger border-opacity-20 text-danger mb-4 rounded-3 p-3">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i> Ada kesalahan input kawan:</h6>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- TOP PANEL: Header SPJ -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-dark text-white overflow-hidden">
            <div class="card-body p-4">
                <div class="row g-4 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label text-white small opacity-50 mb-1">NO. SPJ</label>
                        <input type="text" name="spj_number" class="form-control bg-success bg-opacity-10 border-success text-success fw-bold fs-5" 
                               value="{{ $spjNumber }}" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white small opacity-50 mb-1">MARKET / SALES</label>
                        <select name="sales_market" class="form-select bg-dark border-white border-opacity-10 text-white">
                            <option value="">- Pilih -</option>
                            <option value="MARKETING" {{ old('sales_market') == 'MARKETING' ? 'selected' : '' }}>MARKETING</option>
                            <option value="SALES A" {{ old('sales_market') == 'SALES A' ? 'selected' : '' }}>SALES A</option>
                            <option value="SALES B" {{ old('sales_market') == 'SALES B' ? 'selected' : '' }}>SALES B</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white small opacity-50 mb-1">PETUGAS</label>
                        <div class="d-flex align-items-center gap-2 p-2 border border-white border-opacity-10 rounded-3">
                            <i class="bi bi-person-circle fs-5 text-primary opacity-50"></i>
                            <span class="fw-bold text-white">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-white small opacity-50 mb-1">TANGGAL & JAM</label>
                        <div class="p-2 border border-white border-opacity-10 rounded-3 text-white">
                            <i class="bi bi-clock me-2 text-primary opacity-50"></i> {{ now()->format('d/m/Y - H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- LEFT COLUMN: Customer & Pricing -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 mb-4 h-100">
                    <div class="card-header border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-white"><i class="bi bi-person-circle me-2 text-primary"></i> Data Customer</h6>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCustomer">
                            <i class="bi bi-plus"></i> Customer Baru
                        </button>
                    </div>
                    <div class="card-body p-4 text-white">
                        <div class="mb-4">
                            <label class="form-label text-white fw-medium">Nama Customer *</label>
                            <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                                <option value="">- Ketik nama untuk mencari -</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" data-phone="{{ $c->phone }}" data-address="{{ $c->address }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">No. Telepon</label>
                                <input type="text" id="cust_phone" class="form-control glass-panel" placeholder="Otomatis..." readonly>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-white fw-medium">Alamat</label>
                            <textarea id="cust_address" class="form-control glass-panel" rows="3" placeholder="Pilih customer dulu..." readonly></textarea>
                        </div>

                        <div class="section-title border-top border-secondary border-opacity-25 pt-4 mt-2">
                            <h6 class="fw-bold mb-3 text-white"><i class="bi bi-wallet2 me-2 text-success"></i> Status & Harga</h6>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Status Pembayaran *</label>
                            <div class="d-flex gap-2">
                                <input type="radio" class="btn-check" name="payment_status" id="pay_lunas" value="Lunas" {{ old('payment_status', 'Lunas') == 'Lunas' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success flex-grow-1" for="pay_lunas"><i class="bi bi-check-circle me-1"></i> Lunas</label>
                                <input type="radio" class="btn-check" name="payment_status" id="pay_dp" value="DP" {{ old('payment_status') == 'DP' ? 'checked' : '' }}>
                                <label class="btn btn-outline-warning flex-grow-1" for="pay_dp"><i class="bi bi-hourglass-split me-1"></i> DP</label>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label text-white fw-medium">Harga / Hari *</label>
                                <input type="number" name="price_per_day" id="price_day" class="form-control glass-panel @error('price_per_day') is-invalid @enderror" placeholder="Rp 0" value="{{ old('price_per_day') }}">
                            </div>
                            <div class="col-6">
                                <label class="form-label text-white fw-medium">X Hari *</label>
                                <input type="number" name="days_count" id="days_count" class="form-control glass-panel @error('days_count') is-invalid @enderror" value="{{ old('days_count', 1) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-white text-info">Uang Muka / DP Awal (Rp)</label>
                                <input type="number" name="initial_payment" id="initial_payment" class="form-control glass-panel border-info border-opacity-50 text-info fw-bold" placeholder="Kosongkan jika Lunas..." value="{{ old('initial_payment') }}">
                                <small class="text-info opacity-50 italic">Khusus untuk status DP kawan.</small>
                            </div>
                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold text-white">Tarif Total *</label>
                                <input type="number" name="total_price" id="total_price" class="form-control bg-success bg-opacity-10 border-success border-opacity-25 text-success fw-bold fs-4" readonly value="{{ old('total_price') }}">
                                <small class="text-white opacity-50 italic">Otomatis dari harga x hari, bisa diedit kawan.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Order Detail -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0 text-white"><i class="bi bi-file-earmark-check me-2 text-warning"></i> Detail Job Order</h6>
                    </div>
                    <div class="card-body p-4 text-white">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">Pilih Kendaraan *</label>
                                <select name="unit_id" id="unit_id" class="form-select @error('unit_id') is-invalid @enderror" required>
                                    <option value="">- Pilih kendaraan -</option>
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}" data-nopol="{{ $u->nopol }}" data-year="{{ $u->year }}" {{ old('unit_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white fw-medium">No. Polisi</label>
                                <input type="text" id="unit_nopol" class="form-control glass-panel" placeholder="Otomatis..." readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label text-white fw-medium">Driver *</label>
                                <select name="driver_id" class="form-select @error('driver_id') is-invalid @enderror" required>
                                    <option value="">- Pilih Driver -</option>
                                    @foreach($drivers as $d)
                                        <option value="{{ $d->id }}" {{ old('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                @error('driver_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-white fw-medium">Tujuan *</label>
                            <input type="text" name="destination" class="form-control glass-panel @error('destination') is-invalid @enderror" placeholder="Bandara, Hotel, Nama Lokasi..." value="{{ old('destination') }}">
                            @error('destination') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label text-white fw-medium">Jam Berangkat *</label>
                                <input type="time" name="departure_time" class="form-control glass-panel @error('departure_time') is-invalid @enderror" value="{{ old('departure_time') }}">
                                @error('departure_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-white fw-medium">Tgl Berangkat *</label>
                                <input type="date" name="departure_date" class="form-control glass-panel @error('departure_date') is-invalid @enderror" value="{{ old('departure_date') }}">
                                @error('departure_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-white fw-medium">Tgl Kembali *</label>
                                <input type="date" name="return_date" class="form-control glass-panel @error('return_date') is-invalid @enderror" value="{{ old('return_date') }}">
                                @error('return_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-white fw-medium">Keterangan / Catatan</label>
                            <textarea name="description" class="form-control glass-panel" rows="4" placeholder="Catatan tambahan...">{{ old('description') }}</textarea>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary btn-lg py-3 rounded-4 shadow fw-bold">
                                <i class="bi bi-save2-fill me-2"></i> SIMPAN & CETAK SPJ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal Quick Add Customer -->
    <div class="modal fade" id="modalCustomer" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 bg-dark text-white">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-white">Tambah Customer Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect_to" value="{{ route('job-orders.create') }}">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label text-white fw-medium">NIK (16 Digit) *</label>
                            <input type="text" name="nik" class="form-control glass-panel @error('nik') is-invalid @enderror" required maxlength="16" value="{{ old('nik') }}">
                            @error('nik') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white fw-medium">Nama Lengkap *</label>
                            <input type="text" name="name" class="form-control glass-panel @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                             @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white fw-medium">No. Telepon *</label>
                            <input type="text" name="phone" class="form-control glass-panel @error('phone') is-invalid @enderror" required value="{{ old('phone') }}">
                             @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-white fw-medium">Alamat *</label>
                            <textarea name="address" class="form-control glass-panel @error('address') is-invalid @enderror" rows="2" required>{{ old('address') }}</textarea>
                             @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill">Daftarkan & Pilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customer_id, #unit_id, select[name="driver_id"]').select2({
                width: '100%'
            });
        });

        // Auto fill customer data
        $('#customer_id').on('change', function() {
            const opt = this.options[this.selectedIndex];
            document.getElementById('cust_phone').value = opt.dataset.phone || '';
            document.getElementById('cust_address').value = opt.dataset.address || '';
        });

        // Auto fill unit data
        $('#unit_id').on('change', function() {
            const opt = this.options[this.selectedIndex];
            document.getElementById('unit_nopol').value = opt.dataset.nopol || '';
        });

        // UI Inputs
        const priceInput = document.getElementById('price_day');
        const daysInput = document.getElementById('days_count');
        const totalInput = document.getElementById('total_price');
        const departureDateInput = document.querySelector('input[name="departure_date"]');
        const returnDateInput = document.querySelector('input[name="return_date"]');

        function calculateDays() {
            const start = departureDateInput.value;
            const end = returnDateInput.value;

            if (start && end) {
                const date1 = new Date(start);
                const date2 = new Date(end);
                
                // Hitung selisih kawan
                const diffTime = date2 - date1;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                // Logika: Hari yang sama = 1 hari, Besoknya = 2 hari kawan
                const totalDays = diffDays >= 0 ? diffDays + 1 : 1;
                daysInput.value = totalDays;
                
                calculateTotal();
            }
        }

        function calculateTotal() {
            const price = parseFloat(priceInput.value) || 0;
            const days = parseInt(daysInput.value) || 0;
            totalInput.value = price * days;
        }

        priceInput?.addEventListener('input', calculateTotal);
        daysInput?.addEventListener('input', calculateTotal);
        departureDateInput?.addEventListener('change', calculateDays);
        returnDateInput?.addEventListener('change', calculateDays);

        // Keep Quick Customer modal open if there are errors
        @if($errors->has('nik') || $errors->has('name') || $errors->has('phone') || $errors->has('address'))
            const customerModal = new bootstrap.Modal(document.getElementById('modalCustomer'));
            customerModal.show();
        @endif
    </script>
    @endpush
</x-app-layout>
