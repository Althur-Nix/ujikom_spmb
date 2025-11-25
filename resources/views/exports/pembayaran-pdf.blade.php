<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        h2 { text-align: center; }
        .total { font-weight: bold; text-align: right; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Laporan Pembayaran Pendaftaran</h2>
    <p>Tanggal: {{ date('d-m-Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Pendaftaran</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Biaya</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftar as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->no_pendaftaran }}</td>
                <td>{{ $p->dataSiswa->nama ?? '-' }}</td>
                <td>{{ $p->jurusan->nama ?? '-' }}</td>
                <td>Rp {{ number_format($p->gelombang->biaya_daftar ?? 0, 0, ',', '.') }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total Pembayaran: Rp {{ number_format($total, 0, ',', '.') }}</p>
</body>
</html>
