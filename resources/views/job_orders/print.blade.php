<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SURAT PERINTAH JALAN - {{ $jobOrder->spj_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .container {
            border: 2px solid #2c3e50;
            padding: 20px;
            position: relative;
            min-height: 26cm;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px double #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .brand {
            display: flex;
            align-items: center;
        }
        .logo-box {
            background: #2c3e50;
            color: #fff;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14pt;
            border-radius: 12px;
            margin-right: 15px;
            text-align: center;
            line-height: 1.1;
        }
        .company-details h1 {
            color: #2c3e50;
            font-size: 22pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .company-details p {
            margin: 2px 0;
            font-size: 9pt;
            color: #666;
        }
        .doc-info {
            text-align: right;
        }
        .doc-title {
            font-size: 16pt;
            font-weight: 800;
            color: #2c3e50;
            margin: 0;
        }
        .doc-number {
            font-size: 11pt;
            font-weight: bold;
            color: #e67e22;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #f8f9fa;
            border-left: 5px solid #2c3e50;
            padding: 5px 15px;
            font-weight: 800;
            margin-bottom: 10px;
            font-size: 11pt;
            text-transform: uppercase;
            color: #2c3e50;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 0 10px;
        }
        .info-item {
            display: flex;
            border-bottom: 1px dotted #ccc;
            padding: 5px 0;
        }
        .label {
            width: 40%;
            font-weight: bold;
            color: #7f8c8d;
            font-size: 9pt;
        }
        .value {
            width: 60%;
            font-weight: 600;
            color: #2c3e50;
        }
        .summary-box {
            background: #fdf2e9;
            border: 1px solid #e67e22;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .summary-label {
            font-weight: 800;
            font-size: 12pt;
            color: #d35400;
        }
        .summary-value {
            font-size: 16pt;
            font-weight: 800;
            color: #2c3e50;
        }
        .footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .sig-col {
            text-align: center;
            width: 200px;
        }
        .sig-label {
            font-weight: bold;
            margin-bottom: 60px;
            display: block;
        }
        .sig-name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            display: inline-block;
            min-width: 150px;
        }
        .qr-placeholder {
            width: 50px;
            height: 50px;
            border: 1px solid #eee;
            margin: 5px auto;
            opacity: 0.3;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #2c3e50;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: 0.3s;
        }
        .print-btn:hover { background: #34495e; }
        @media print {
            .print-btn { display: none; }
            .container { border: 2px solid #2c3e50; min-height: auto; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Cetak SPJ Resmi</button>

    <div class="container">
        <div class="header">
            <div class="brand">
                <div class="logo-box">IMAM<br>RENTAL</div>
                <div class="company-details">
                    <h1>IMAM RENTAL</h1>
                    <p>Penyedia Jasa Transportasi & Tour Travel Terpercaya</p>
                    <p>Jl. Utama No. 88, Kawasan Wisata Bahari, Indonesia</p>
                    <p>Hubungi: 0812-7777-8888 | Email: cs@imamrental.com</p>
                </div>
            </div>
            <div class="doc-info">
                <p class="doc-title">SURAT PERINTAH JALAN (SPJ)</p>
                <span class="doc-number">#{{ $jobOrder->spj_number }}</span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">A. Detail Pelanggan & Perjalanan</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Nama Customer</span>
                    <span class="value">{{ strtoupper($jobOrder->customer->name) }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Tujuan Utama</span>
                    <span class="value">{{ strtoupper($jobOrder->destination) }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Tanggal Berangkat</span>
                    <span class="value">{{ \Carbon\Carbon::parse($jobOrder->departure_date)->format('d F Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Jam Penjemputan</span>
                    <span class="value">{{ \Carbon\Carbon::parse($jobOrder->departure_time)->format('H:i') }} WIB</span>
                </div>
                <div class="info-item">
                    <span class="label">Tanggal Kembali</span>
                    <span class="value">{{ \Carbon\Carbon::parse($jobOrder->return_date)->format('d F Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Estimasi Durasi</span>
                    <span class="value">{{ $jobOrder->days_count }} Hari</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">B. Armada & Kru Lapangan</div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label">Driver Utama</span>
                    <span class="value">{{ strtoupper($jobOrder->driver->name) }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Kode Unit</span>
                    <span class="value">{{ $jobOrder->unit->unit_code ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Jenis Armada</span>
                    <span class="value">{{ strtoupper($jobOrder->unit->name) }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Nomor Polisi (Nopol)</span>
                    <span class="value">{{ $jobOrder->unit->nopol }}</span>
                </div>
            </div>
        </div>

        @if($jobOrder->description)
        <div class="section">
            <div class="section-title">Catatan Tambahan</div>
            <div style="padding: 10px; background: #fff; font-style: italic; border: 1px dashed #ccc;">
                "{{ $jobOrder->description }}"
            </div>
        </div>
        @endif

        <div class="section">
            <div class="summary-box">
                <div style="flex-grow: 1;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="color: #d35400; font-weight: bold;">TOTAL TARIF SEWA</span>
                        <span style="font-weight: 800;">Rp {{ number_format($jobOrder->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px; border-bottom: 1px solid #e67e22; padding-bottom: 5px;">
                        <span style="color: #27ae60; font-weight: bold;">SUDAH TERBAYAR</span>
                        <span style="font-weight: 800; color: #27ae60;">Rp {{ number_format($jobOrder->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                        <span class="summary-label">SISA PEMBAYARAN</span>
                        <span class="summary-value">Rp {{ number_format($jobOrder->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="sig-col">
                <span class="sig-label">Dibuat Oleh:</span>
                <div class="qr-placeholder">QR</div>
                <span class="sig-name">{{ strtoupper(Auth::user()->name) }}</span>
                <br><small>Admin Operasional</small>
            </div>

            <div style="text-align: center; font-size: 8pt; color: #999;">
                Dicetak pada: {{ date('d/m/Y H:i') }}<br>
                Sistem Manajemen Kasir v1.0
            </div>

            <div class="sig-col">
                <span class="sig-label">Penerima / Customer:</span>
                <div style="height: 50px;"></div>
                <span class="sig-name">(.....................................)</span>
                <br><small>Tanda Tangan & Nama Terang</small>
            </div>
        </div>
    </div>
</body>
</html>
