<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <title>Export Pendaftar</title>
    <style>
      body { font-family: DejaVu Sans, sans-serif; font-size:12px; }
      .center { text-align:center; }
      .small { font-size:10px; color:#333; }
      table { width:100%; border-collapse:collapse; margin-top:12px; }
      th, td { border:1px solid #000; padding:6px 8px; text-align:left; }
      th { background:#eee; font-weight:600; }
      .no-data { text-align:center; padding:12px; }
    </style>
  </head>
  <body>
    <h3 class="center">Laporan Pendaftar</h3>
    <p class="small">Tanggal export: {{ now()->format('Y-m-d H:i') }}</p>

    <table>
      <thead>
        <tr>
          <th style="width:30px">#</th>
          <th>No. Pendaftaran</th>
          <th>Nama</th>
          <th>NIK</th>
          <th>Jurusan</th>
          <th>Gelombang</th>
          <th>Tanggal Daftar</th>
          <th>Status</th>
          <th>Asal Sekolah</th>
          <th>HP</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $r->no_pendaftaran ?? '-' }}</td>
            <td>{{ $r->nama ?? optional($r->user)->name ?? '-' }}</td>
            <td>{{ optional($r->dataSiswa)->nik ?? '-' }}</td>
            <td>{{ optional($r->jurusan)->nama ?? '-' }}</td>
            <td>{{ optional($r->gelombang)->nama ?? '-' }}</td>
            <td>
              @php
                $dt = $r->tanggal_daftar ?? $r->created_at;
              @endphp
              {{ $dt ? \Carbon\Carbon::parse($dt)->format('Y-m-d H:i') : '-' }}
            </td>
            <td>{{ $r->status ?? '-' }}</td>
            <td>{{ optional($r->dataSiswa)->asal_sekolah ?? '-' }}</td>
            <td>{{ optional($r->user)->hp ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="10" class="no-data">Tidak ada data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </body>
</html>

