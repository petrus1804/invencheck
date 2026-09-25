<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bukti Pengambilan Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #10131a; padding: 20px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #10131a; padding-bottom: 16px; }
        .header h1 { font-size: 18px; margin: 0 0 4px; }
        .header p { margin: 0; color: #6b7280; font-size: 11px; }
        .code { text-align: center; font-family: monospace; font-size: 14px; font-weight: bold; margin-bottom: 20px; letter-spacing: 2px; }
        table.detail { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        table.detail td { padding: 8px 4px; vertical-align: top; }
        table.detail td.label { width: 160px; color: #6b7280; }
        table.detail td.value { font-weight: bold; }
        .status-approved { display: inline-block; padding: 4px 12px; background: #e6f6ef; color: #1fa97a; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .signature-area { display: table; width: 100%; margin-top: 40px; }
        .signature-box { display: table-cell; width: 50%; text-align: center; }
        .signature-line { margin-top: 60px; border-top: 1px solid #10131a; padding-top: 6px; width: 80%; margin-left: auto; margin-right: auto; }
        .footer-note { margin-top: 30px; font-size: 10.5px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h1>BUKTI PENGAMBILAN BARANG</h1>
        <p>InvenCheck Warehouse System</p>
    </div>

    <div class="code">
        TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}
    </div>

    <table class="detail">
        <tr>
            <td class="label">Nama Pemohon</td>
            <td class="value">: {{ $transaction->user->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Barang</td>
            <td class="value">: {{ $transaction->item->name ?? '-' }} ({{ $transaction->item->sku ?? '-' }})</td>
        </tr>
        <tr>
            <td class="label">Jumlah</td>
            <td class="value">: {{ $transaction->quantity }} {{ $transaction->item->unit ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Gudang</td>
            <td class="value">: {{ $transaction->item->warehouse->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Keperluan</td>
            <td class="value">: {{ $transaction->purpose }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Diajukan</td>
            <td class="value">: {{ $transaction->created_at->translatedFormat('d F Y, H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Disetujui Oleh</td>
            <td class="value">: {{ $transaction->approver->name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="value"><span class="status-approved">DISETUJUI</span></td>
        </tr>
    </table>

    <div class="signature-area">
        <div class="signature-box">
            <div>Pemohon,</div>
            <div class="signature-line">{{ $transaction->user->name ?? '-' }}</div>
        </div>
        <div class="signature-box">
            <div>Petugas Gudang,</div>
            <div class="signature-line">&nbsp;</div>
        </div>
    </div>

    <div class="footer-note">
        Tunjukkan bukti ini ke petugas gudang saat pengambilan barang fisik.<br>
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }}
    </div>

</body>
</html>