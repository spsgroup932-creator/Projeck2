<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #000; font-size: 16pt; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; font-size: 10pt; }
        
        .info-table { width: 100%; margin-bottom: 15px; }
        .info-table td { border: none !important; padding: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { 
            background-color: #f2f2f2; 
            color: #333; 
            font-weight: bold; 
            padding: 8px; 
            border: 1px solid #999;
            text-align: left;
            font-size: 9pt;
        }
        td { 
            padding: 7px 8px; 
            border: 1px solid #ccc;
            font-size: 9pt;
            vertical-align: top;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        
        .total-row { background-color: #eee; font-weight: bold; }
        .total-row td { border-top: 1.5px solid #444; }
        
        .footer { margin-top: 30px; font-size: 8pt; color: #777; width: 100%; border-top: 1px solid #eee; padding-top: 10px; }
        .signature-box { margin-top: 40px; float: right; width: 200px; text-align: center; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>Laporan Operasional Keuangan - Sistem SaaS Rental</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Periode</strong></td>
            <td width="35%">: {{ $periode }}</td>
            <td width="20%"><strong>Tgl Cetak</strong></td>
            <td width="30%">: {{ date('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>User</strong></td>
            <td>: {{ auth()->user()->name }}</td>
            <td><strong>Cabang</strong></td>
            <td>: {{ auth()->user()->branch->name ?? 'Pusat' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="12%">Tanggal</th>
                <th width="18%">No. SPJ</th>
                <th width="25%">Customer</th>
                <th width="20%">Unit</th>
                <th width="10%">Metode</th>
                <th class="text-end" width="15%">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $p)
            <tr>
                <td class="text-center">{{ date('d/m/Y', strtotime($p->payment_date)) }}</td>
                <td class="fw-bold">{{ $p->jobOrder->spj_number }}</td>
                <td>{{ $p->jobOrder->customer->name }}</td>
                <td>{{ $p->jobOrder->unit->name }}</td>
                <td class="text-center">{{ strtoupper($p->method) }}</td>
                <td class="text-end fw-bold">
                    {{ number_format($p->amount, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada transaksi pada periode ini kawan.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-end fw-bold">TOTAL PENDAPATAN</td>
                <td class="text-end fw-bold">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-box">
        <p>Dicetak pada: {{ date('d F Y') }}</p>
        <div class="signature-space"></div>
        <p><strong>( {{ auth()->user()->name }} )</strong></p>
        <p>Admin Operasional</p>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
        <p>* Laporan ini dihasilkan secara digital oleh sistem manajemen rental kawan.</p>
    </div>
</body>
</html>
