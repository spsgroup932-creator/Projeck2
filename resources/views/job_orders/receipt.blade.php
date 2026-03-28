<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk SPJ - {{ $jobOrder->spj_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: 0;
            padding: 5mm;
            background: #fff;
            font-size: 10pt;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
        }
        .info {
            font-size: 8pt;
            margin: 2px 0;
        }
        .title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0;
            text-decoration: underline;
        }
        .data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        .label {
            width: 35%;
        }
        .value {
            width: 65%;
            text-align: right;
            font-weight: bold;
            word-wrap: break-word;
        }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }
        .financial-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 8pt;
        }
        @media print {
            body { padding: 0; }
            .print-btn { display: none; }
        }
        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
</head>
<body onload="window.print()">
    <button class="print-btn" onclick="window.print()">PRINT</button>

    <div class="header">
        <h1 class="company-name">IMAM RENTAL</h1>
        <p class="info">Tour & Travel Transport</p>
        <p class="info">Jl. Utama No. 88, Kota Wisata</p>
        <p class="info">Tlp: 0812-7777-8888</p>
    </div>

    <div class="title">STRUK JOB ORDER</div>

    <div class="data-row"><span class="label">NO SPJ</span><span class="value">{{ $jobOrder->spj_number }}</span></div>
    <div class="data-row"><span class="label">DATE</span><span class="value">{{ date('d/m/Y H:i') }}</span></div>
    
    <div class="divider"></div>

    <div class="data-row"><span class="label">CUSTOMER</span><span class="value">{{ strtoupper($jobOrder->customer->name) }}</span></div>
    <div class="data-row"><span class="label">TUJUAN</span><span class="value">{{ strtoupper($jobOrder->destination) }}</span></div>
    <div class="data-row"><span class="label">DURASI</span><span class="value">{{ $jobOrder->days_count }} Hari</span></div>
    <div class="data-row"><span class="label">BERANGKAT</span><span class="value">{{ date('d/m/Y', strtotime($jobOrder->departure_date)) }}</span></div>
    <div class="data-row"><span class="label">KEMBALI</span><span class="value">{{ date('d/m/Y', strtotime($jobOrder->return_date)) }}</span></div>
    
    <div class="divider"></div>

    <div class="data-row"><span class="label">UNIT</span><span class="value">{{ strtoupper($jobOrder->unit->name) }}</span></div>
    <div class="data-row"><span class="label">NOPOL</span><span class="value">{{ $jobOrder->unit->nopol }}</span></div>
    <div class="data-row"><span class="label">DRIVER</span><span class="value">{{ strtoupper($jobOrder->driver->name) }}</span></div>

    <div class="divider"></div>

    <div class="data-row"><span class="label">TOTAL TARIF</span><span class="value">Rp{{ number_format($jobOrder->total_price, 0, ',', '.') }}</span></div>
    <div class="data-row"><span class="label">TERBAYAR</span><span class="value">Rp{{ number_format($jobOrder->paid_amount, 0, ',', '.') }}</span></div>
    <div class="financial-row"><span class="label">SISA</span><span class="value">Rp{{ number_format($jobOrder->remaining_balance, 0, ',', '.') }}</span></div>

    <div class="divider"></div>

    <div class="footer">
        <p>*** TERIMA KASIH ***</p>
        <p>SEMOGA PERJALANAN ANDA MENYENANGKAN</p>
        <p>Printed by system IMAM RENTAL</p>
    </div>
</body>
</html>
