@extends('kepala-sekolah.layout')

@section('title', 'Siswa Diterima')

@section('content')
<div class="content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-check-circle me-2"></i>Siswa Diterima
        </div>
        <div class="btn-group">
            <a href="{{ route('export.pendaftar.excel', ['status' => 'ACCEPTED']) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </a>
            <a href="{{ route('export.pendaftar.pdf', ['status' => 'ACCEPTED']) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i>Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert alert-success border-0" style="background: #e8f5e9; color: #27ae60;">
            <i class="fas fa-info-circle me-2"></i>Total siswa yang diterima: <strong>{{ $pendaftar->total() }}</strong>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Nama</th>
                        <th>NISN</th>
                        <th>Jurusan</th>
                        <th>Asal Sekolah</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftar as $p)
                    <tr>
                        <td><strong>{{ $p->no_pendaftaran ?? '-' }}</strong></td>
                        <td>{{ $p->dataSiswa->nama ?? $p->user->name }}</td>
                        <td>{{ $p->dataSiswa->nisn ?? '-' }}</td>
                        <td>{{ $p->jurusan->nama ?? '-' }}</td>
                        <td>{{ $p->asalSekolah->nama_sekolah ?? '-' }}</td>
                        <td>
                            @if($p->dataSiswa)
                                {{ $p->dataSiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $p->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada siswa yang diterima</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $pendaftar->links() }}
        </div>
    </div>
</div>
@endsection
