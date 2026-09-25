<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - InvenCheck</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #10131a; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        p.subtitle { color: #6b7280; margin-top: 0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e4e6ec; padding: 6px 8px; text-align: left; }
        th { background: #f2f3f6; }
        .in { color: #1fa97a; font-weight: bold; }
        .out { color: #e14b4b; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laporan Transaksi - InvenCheck</h1>
    <p class="subtitle">Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Gudang</th>
                <th>Petugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
                <tr>
                    <td>{{ $trx->created_at->translatedFormat('d M Y') }}</td>
                    <td>{{ $trx->item->sku ?? '-' }}</td>
                    <td>{{ $trx->item->name ?? '-' }}</td>
                    <td class="{{ $trx->type }}">{{ $trx->type === 'in' ? 'Masuk' : 'Keluar' }}</td>
                    <td>{{ $trx->type === 'in' ? '+' : '-' }}{{ $trx->quantity }}</td>
                    <td>{{ $trx->item->warehouse->name ?? '-' }}</td>
                    <td>{{ $trx->user->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>