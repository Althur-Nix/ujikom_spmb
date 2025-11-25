@extends('panitia.layout')

@section('title', 'Verifikasi Berkas')

@section('content')
<div class="content-card">
    <div class="card-header">
        <i class="fas fa-check-circle me-2"></i>Verifikasi Berkas
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftar as $index => $p)
                    <tr>
                        <td>{{ $pendaftar->firstItem() + $index }}</td>
                        <td>{{ $p->dataSiswa->nama ?? $p->user->name ?? '-' }}</td>
                        <td>{{ $p->user->email ?? '-' }}</td>
                        <td>{{ $p->jurusan->nama ?? '-' }}</td>
                        <td>
                            @php
                                $hasBerkas = $p->berkas && $p->berkas->where('is_draft', false)->count() > 0;
                            @endphp
                            @if(!$hasBerkas)
                                <span class="badge bg-secondary">Belum Upload Berkas</span>
                            @else
                                @switch($p->status)
                                    @case('SUBMIT')
                                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                        @break
                                    @case('REVISION_REQUIRED')
                                        <span class="badge bg-warning">Perlu Revisi</span>
                                        @break
                                    @case('ADM_PASS')
                                        <span class="badge bg-success">Berkas Diterima</span>
                                        @break
                                    @case('ADM_REJECT')
                                        <span class="badge bg-danger">Berkas Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $p->status }}</span>
                                @endswitch
                            @endif
                        </td>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('panitia.verifikasi.show', $p->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i>Verifikasi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada berkas yang menunggu verifikasi</td>
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