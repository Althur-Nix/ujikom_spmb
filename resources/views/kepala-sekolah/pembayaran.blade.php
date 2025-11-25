@extends('kepala-sekolah.layout')

@section('title', 'Rekap Pembayaran')

@section('content')
<div class="content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-money-bill-wave me-2"></i>Rekap Pembayaran
        </div>
        <div class="btn-group">
            <a href="{{ route('export.pembayaran.excel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i>Export Excel
            </a>
            <a href="{{ route('export.pembayaran.pdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i>Export PDF
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="alert border-0" style="background: #fff3e0; color: #e67e22;">
            <i class="fas fa-info-circle me-2"></i>Total pembayaran yang sudah masuk: 
            <strong>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No. Pendaftaran</th>
                        <th>Nama</th>
                        <th>Bank Tujuan</th>
                        <th>Nama Pengirim</th>
                        <th>Nominal</th>
                        <th>Tanggal Transfer</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayaran as $p)
                    <tr>
                        <td><strong>{{ $p->pendaftar->no_pendaftaran ?? '-' }}</strong></td>
                        <td>{{ optional($p->pendaftar)->dataSiswa->nama ?? optional($p->pendaftar)->user->name ?? '-' }}</td>
                        <td>{{ $p->bank_tujuan ?? '-' }}</td>
                        <td>{{ $p->nama_pengirim ?? '-' }}</td>
                        <td><strong>Rp {{ number_format($p->nominal ?? 0, 0, ',', '.') }}</strong></td>
                        <td>{{ $p->tanggal_transfer ? (is_string($p->tanggal_transfer) ? date('d/m/Y', strtotime($p->tanggal_transfer)) : $p->tanggal_transfer->format('d/m/Y')) : '-' }}</td>
                        <td>
                            @if($p->status == 'VERIFIED')
                                <span class="badge bg-success">Terverifikasi</span>
                            @elseif($p->status == 'PENDING')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @else
                                <span class="badge bg-secondary">{{ $p->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada pembayaran yang masuk</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $pembayaran->links() }}
        </div>
    </div>
</div>
@endsection
