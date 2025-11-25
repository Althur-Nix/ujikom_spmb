<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #e67e22; color: white; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-box { margin-top: 20px; padding: 10px; background: #f8f9fa; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h2>LAPORAN REKAP PEMBAYARAN</h2>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    <p>Total Data: {{ $pembayaran->count() }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pendaftaran</th>
                <th>Nama</th>
                <th>Bank Tujuan</th>
                <th>Nama Pengirim</th>
                <th>Nominal</th>
                <th>Tanggal Transfer</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembayaran as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $p->pendaftar->no_pendaftaran ?? '-' }}</td>
                <td>{{ optional($p->pendaftar->dataSiswa)->nama ?? optional($p->pendaftar->user)->name ?? '-' }}</td>
                <td>{{ $p->bank_tujuan ?? '-' }}</td>
                <td>{{ $p->nama_pengirim ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($p->nominal ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">{{ $p->tanggal_transfer ? (is_string($p->tanggal_transfer) ? date('d/m/Y', strtotime($p->tanggal_transfer)) : $p->tanggal_transfer->format('d/m/Y')) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="total-box">
        <strong>Total Pembayaran: Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong>
    </div>
</body>
</html>
