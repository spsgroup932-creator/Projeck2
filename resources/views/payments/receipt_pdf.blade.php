<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Nota Bayar - {{ $payment->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; font-size: 9pt; width: 100%; margin: 0; padding: 0; }
        .receipt { padding: 10px; border: 1px dashed #000; }
        .header { text-align: center; margin-bottom: 10px; }
        .company { font-weight: bold; font-size: 12pt; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .row { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .label { float: left; width: 40%; }
        .value { float: right; width: 60%; text-align: right; font-weight: bold; }
        .total { font-size: 11pt; font-weight: bold; padding-top: 5px; }
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
        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">BUKTI PEMBAYARAN</div>
        <div class="divider"></div>

        <div class="clearfix"><span class="label">No. SPJ</span><span class="value">{{ $payment->jobOrder->spj_number }}</span></div>
        <div class="clearfix"><span class="label">Tanggal</span><span class="value">{{ date('d/m/Y H:i', strtotime($payment->payment_date)) }}</span></div>
        <div class="clearfix"><span class="label">Customer</span><span class="value">{{ strtoupper($payment->jobOrder->customer->name) }}</span></div>
        <div class="clearfix"><span class="label">Metode</span><span class="value">{{ $payment->method }}</span></div>
        
        <div class="divider"></div>

        <div class="clearfix total">
            <span class="label">JUMLAH BAYAR</span>
            <span class="value">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
        </div>
        
        <div class="divider"></div>

        <div class="footer">
            <p>Terima kasih atas pembayarannya kawan!</p>
            <p>Simpan nota ini sebagai bukti sah kawan.</p>
            <p style="font-style: italic;">Admin: {{ $payment->user->name ?? 'System' }}</p>
        </div>
    </div>
</body>
</html>
