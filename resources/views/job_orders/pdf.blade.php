<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>SPJ - {{ $jobOrder->spj_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; line-height: 1.4; color: #333; }
        .container { border: 1px solid #2c3e50; padding: 15px; }
        table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; }
        .logo-box { background: #2c3e50; color: #fff; width: 60px; height: 60px; text-align: center; font-weight: bold; padding-top: 15px; border-radius: 8px; }
        .company-name { font-size: 18pt; font-weight: bold; color: #2c3e50; margin: 0; }
        .company-tag { font-size: 9pt; color: #666; margin: 0; }
        .doc-title { text-align: right; font-size: 14pt; font-weight: bold; color: #2c3e50; }
        .doc-num { text-align: right; color: #e67e22; font-weight: bold; }
        .section-title { background: #f8f9fa; border-left: 5px solid #2c3e50; padding: 5px 10px; font-weight: bold; margin: 15px 0 10px 0; text-transform: uppercase; font-size: 10pt; }
        .data-table td { padding: 3px 0; border-bottom: 1px dotted #eee; }
        .label { width: 35%; color: #7f8c8d; font-weight: bold; font-size: 9pt; }
        .value { width: 65%; font-weight: bold; color: #2c3e50; }
        .summary-box { background: #fdf2e9; border: 1px solid #e67e22; padding: 10px; margin-top: 20px; }
        .footer-table { margin-top: 50px; text-align: center; }
        .sig-name { border-top: 1px solid #000; padding-top: 5px; font-weight: bold; display: inline-block; width: 150px; }
    </style>
</head>
<body>
    <div class="container">
        <table class="header-table">
            <tr>
                <td width="70">
                    <div class="logo-box">IMAM<br>RENTAL</div>
                </td>
                <td>
                    <h1 class="company-name">IMAM RENTAL</h1>
                    <p class="company-tag">Jasa Transportasi & Tour Travel Terpercaya</p>
                    <p style="font-size: 8pt; margin: 0;">Jl. Utama No. 88, Kawasan Wisata Bahari | 0812-7777-8888</p>
                </td>
                <td align="right">
                    <div class="doc-title">SURAT PERINTAH JALAN</div>
                    <div class="doc-num">#{{ $jobOrder->spj_number }}</div>
                </td>
            </tr>
        </table>

        <div class="section-title">A. Detail Pelanggan & Perjalanan</div>
        <table>
            <tr>
                <td width="50%">
                    <table class="data-table">
                        <tr><td class="label">Nama Customer</td><td class="value">{{ strtoupper($jobOrder->customer->name) }}</td></tr>
                        <tr><td class="label">Tanggal Berangkat</td><td class="value">{{ \Carbon\Carbon::parse($jobOrder->departure_date)->format('d F Y') }}</td></tr>
                        <tr><td class="label">Jam Penjemputan</td><td class="value">{{ \Carbon\Carbon::parse($jobOrder->departure_time)->format('H:i') }} WIB</td></tr>
                    </table>
                </td>
                <td width="50%">
                    <table class="data-table">
                        <tr><td class="label">Tujuan Utama</td><td class="value">{{ strtoupper($jobOrder->destination) }}</td></tr>
                        <tr><td class="label">Tanggal Kembali</td><td class="value">{{ \Carbon\Carbon::parse($jobOrder->return_date)->format('d F Y') }}</td></tr>
                        <tr><td class="label">Durasi</td><td class="value">{{ $jobOrder->days_count }} Hari</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="section-title">B. Armada & Kru Lapangan</div>
        <table>
            <tr>
                <td width="50%">
                    <table class="data-table">
                        <tr><td class="label">Driver Utama</td><td class="value">{{ strtoupper($jobOrder->driver->name) }}</td></tr>
                        <tr><td class="label">Jenis Armada</td><td class="value">{{ strtoupper($jobOrder->unit->name) }}</td></tr>
                    </table>
                </td>
                <td width="50%">
                    <table class="data-table">
                        <tr><td class="label">Kode Unit</td><td class="value">{{ $jobOrder->unit->unit_code ?? '-' }}</td></tr>
                        <tr><td class="label">No Polisi</td><td class="value">{{ $jobOrder->unit->nopol }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        @if($jobOrder->description)
        <div class="section-title">Catatan</div>
        <div style="font-style: italic; color: #555; padding: 5px;">"{{ $jobOrder->description }}"</div>
        @endif

        <div class="summary-box">
            <table style="width: 100%;">
                <tr>
                    <td style="color: #d35400; font-weight: bold;">TOTAL TARIF SEWA</td>
                    <td align="right" style="font-weight: bold;">Rp {{ number_format($jobOrder->total_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color: #27ae60; font-weight: bold; border-bottom: 1px solid #e67e22; padding: 5px 0;">SUDAH TERBAYAR</td>
                    <td align="right" style="font-weight: bold; color: #27ae60; border-bottom: 1px solid #e67e22;">Rp {{ number_format($jobOrder->paid_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; font-size: 12pt; padding-top: 5px;">SISA PEMBAYARAN</td>
                    <td align="right" style="font-weight: bold; font-size: 12pt; padding-top: 5px;">Rp {{ number_format($jobOrder->remaining_balance, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <table class="footer-table">
            <tr>
                <td width="33%">
                    <p>Dibuat Oleh:</p>
                    <div style="height: 50px;"></div>
                    <span class="sig-name">{{ strtoupper(Auth::user()->name) }}</span>
                </td>
                <td width="33%" style="font-size: 8pt; color: #999;">
                    <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
                    <p>Dokumen sah hasil sistem kawan.</p>
                </td>
                <td width="33%">
                    <p>Penerima:</p>
                    <div style="height: 50px;"></div>
                    <span class="sig-name">(.....................)</span>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
