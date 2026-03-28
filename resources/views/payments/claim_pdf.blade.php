<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nota Claim - {{ $claim->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 9pt; width: 100%; margin: 0; padding: 0; }
        .receipt { padding: 10px; border: 1px dashed #000; }
        .header { text-align: center; margin-bottom: 10px; }
        .company { font-weight: bold; font-size: 12pt; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .label { float: left; width: 40%; }
        .value { float: right; width: 60%; text-align: right; font-weight: bold; }
        .total { font-size: 11pt; font-weight: bold; padding-top: 5px; color: #d00; }
        .footer { text-align: center; margin-top: 15px; font-size: 8pt; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="company">IMAM RENTAL</div>
            <div style="font-size: 7pt;">Kawasan Wisata Bahari | 0812-7777-8888</div>
        </div>

        <div class="divider"></div>
        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">NOTA CLAIM KERUSAKAN</div>
        <div class="divider"></div>

        <div class="clearfix"><span class="label">No. SPJ</span><span class="value">{{ $claim->jobOrder->spj_number }}</span></div>
        <div class="clearfix"><span class="label">Tanggal</span><span class="value">{{ $claim->created_at->format('d/m/Y H:i') }}</span></div>
        <div class="clearfix"><span class="label">Customer</span><span class="value">{{ strtoupper($claim->jobOrder->customer->name) }}</span></div>
        
        <div class="divider"></div>
        <div style="font-weight: bold; margin-top: 5px;">KETERANGAN:</div>
        <div style="margin-bottom: 10px; font-style: italic; border: 1px solid #ccc; padding: 5px;">
            "{{ $claim->description }}"
        </div>

        <div class="clearfix total">
            <span class="label">TOTAL CLAIM</span>
            <span class="value">Rp {{ number_format($claim->amount, 0, ',', '.') }}</span>
        </div>
        
        <div class="divider"></div>

        <div class="footer">
            <p>Laporan kerusakan / kekurangan kawan.</p>
            <p>Mohon segera ditindaklanjuti kawan.</p>
            <p style="font-style: italic;">Admin: {{ $claim->user->name ?? 'System' }}</p>
        </div>
    </div>
</body>
</html>
