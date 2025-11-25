<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendaftar</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #0d6efd; color: white; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>LAPORAN DATA PENDAFTAR</h2>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    <p>Total Data: {{ $pendaftar->count() }}</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pendaftaran</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftar as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $p->no_pendaftaran }}</td>
                <td>{{ $p->dataSiswa->nama ?? '-' }}</td>
                <td>{{ $p->jurusan->nama ?? '-' }}</td>
                <td>
                    @if($p->status == 'ACCEPTED') Diterima
                    @elseif($p->status == 'PAID') Terbayar
                    @elseif($p->status == 'ADM_PASS') Lulus Adm
                    @else {{ $p->status }}
                    @endif
                </td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
