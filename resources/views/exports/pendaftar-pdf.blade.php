<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <p>Tanggal: {{ date('d-m-Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Pendaftaran</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>Jurusan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendaftar as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->no_pendaftaran }}</td>
                <td>{{ $p->dataSiswa->nama ?? '-' }}</td>
                <td>{{ $p->dataSiswa->nisn ?? '-' }}</td>
                <td>{{ $p->jurusan->nama ?? '-' }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
