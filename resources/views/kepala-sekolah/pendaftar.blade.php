@extends('kepala-sekolah.layout')

@section('title', 'Daftar Calon Siswa')

@section('content')
<div class="content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-users me-2"></i>Daftar Calon Siswa
        </div>
        <div class="btn-group">
            <a href="{{ route('export.pendaftar.excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </a>
            <a href="{{ route('export.pendaftar.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i>Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Asal Sekolah</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftar as $p)
                    <tr>
                        <td><strong>{{ $p->no_pendaftaran ?? '-' }}</strong></td>
                        <td>{{ $p->dataSiswa->nama ?? $p->user->name }}</td>
                        <td>{{ $p->jurusan->nama ?? '-' }}</td>
                        <td>{{ $p->asalSekolah->nama_sekolah ?? '-' }}</td>
                        <td>
                            @switch($p->status)
                                @case('SUBMIT')
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                    @break
                                @case('ADM_PASS')
                                    <span class="badge bg-info">Lulus Administrasi</span>
                                    @break
                                @case('ADM_REJECT')
                                    <span class="badge bg-danger">Ditolak Administrasi</span>
                                    @break
                                @case('PAID')
                                    <span class="badge bg-success">Sudah Bayar</span>
                                    @break
                                @case('ACCEPTED')
                                    <span class="badge bg-primary">Diterima</span>
                                    @break
                                @case('REJECTED')
                                    <span class="badge bg-dark">Ditolak</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $p->status }}</span>
                            @endswitch
                        </td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data pendaftar</td>
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
