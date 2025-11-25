@extends('panitia.layout')

@section('title', 'Dashboard Panitia')

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-box blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $totalPendaftar ?? 0 }}</p>
                    <p class="stat-label">Total Pendaftar</p>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $menungguVerifikasi ?? 0 }}</p>
                    <p class="stat-label">Menunggu Verifikasi</p>
                </div>
                <div class="stat-icon orange">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $berkasVerified ?? 0 }}</p>
                    <p class="stat-label">Berkas Diterima</p>
                </div>
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box red">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $berkasDitolak ?? 0 }}</p>
                    <p class="stat-label">Berkas Ditolak</p>
                </div>
                <div class="stat-icon red">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <i class="fas fa-users me-2"></i>Data Pendaftar
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($allPendaftar as $index => $p)
                    <tr>
                        <td>{{ $allPendaftar->firstItem() + $index }}</td>
                        <td>{{ $p->dataSiswa->nama ?? $p->user->name ?? '-' }}</td>
                        <td>{{ $p->user->email ?? '-' }}</td>
                        <td>{{ $p->jurusan->nama ?? '-' }}</td>
                        <td>
                            @php
                                $hasBerkas = $p->berkas && $p->berkas->where('is_draft', false)->count() > 0;
                            @endphp
                            @if(!$hasBerkas && in_array($p->status, ['SUBMIT', 'DRAFT']))
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
                                    @case('PAID')
                                        <span class="badge bg-info">Sudah Bayar</span>
                                        @break
                                    @case('ACCEPTED')
                                        <span class="badge bg-success">Diterima</span>
                                        @break
                                    @case('REJECTED')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $p->status }}</span>
                                @endswitch
                            @endif
                        </td>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada data pendaftar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $allPendaftar->links() }}
        </div>
    </div>
</div>
@endsection
