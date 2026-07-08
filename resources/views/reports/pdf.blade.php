<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 0; }
        p.subtitle { margin-top: 4px; color: #666; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background-color: #f3f4f6; text-transform: uppercase; font-size: 10px; }
        .badge-in { color: #15803d; font-weight: bold; }
        .badge-out { color: #b91c1c; font-weight: bold; }
        .badge-adjustment { color: #a16207; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 10px; color: #999; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Transaksi Stok - Stockify</h1>
    <p class="subtitle">
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
        @if(request('from') || request('to'))
            | Periode: {{ request('from', '...') }} s/d {{ request('to', '...') }}
        @endif
        @if(request('type'))
            | Tipe: {{ ucfirst(request('type')) }}
        @endif
    </p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Dicatat Oleh</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $trx)
                <tr>
                    <td>{{ $trx->date }}</td>
                    <td>{{ $trx->product->name }}</td>
                    <td>{{ $trx->product->category->name ?? '-' }}</td>
                    <td>
                        @if($trx->type === 'in')
                            <span class="badge-in">Masuk</span>
                        @elseif($trx->type === 'out')
                            <span class="badge-out">Keluar</span>
                        @else
                            <span class="badge-adjustment">Penyesuaian</span>
                        @endif
                    </td>
                    <td>{{ $trx->quantity }}</td>
                    <td>{{ ucfirst($trx->status) }}</td>
                    <td>{{ $trx->user->name }}</td>
                    <td>{{ $trx->notes ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <p class="footer">Total {{ $transactions->count() }} transaksi &mdash; Stockify Aplikasi Manajemen Stok Barang</p>
</body>
</html>