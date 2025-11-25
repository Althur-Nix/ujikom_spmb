@extends('keuangan.layout')

@section('title', 'Dashboard Keuangan')

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-box blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number" style="font-size: 20px;">Rp {{ number_format($totalEstimasiBayar ?? 0, 0, ',', '.') }}</p>
                    <p class="stat-label">Total Pembayaran</p>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $sudahBayar ?? 0 }}</p>
                    <p class="stat-label">Sudah Bayar</p>
                </div>
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
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
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box purple">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $menungguPembayaran ?? 0 }}</p>
                    <p class="stat-label">Menunggu Pembayaran</p>
                </div>
                <div class="stat-icon purple">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#verifikasi" type="button">
                    <i class="fas fa-clipboard-check me-1"></i>Verifikasi Pembayaran
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rekap" type="button">
                    <i class="fas fa-chart-bar me-1"></i>Rekap Pembayaran
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#daftar" type="button">
                    <i class="fas fa-list me-1"></i>Daftar Pembayaran
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <!-- Tab Verifikasi -->
            <div class="tab-pane fade show active" id="verifikasi">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Jurusan</th>
                                <th>Gelombang</th>
                                <th>Biaya Daftar</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendaftarMenungguVerifikasi as $index => $pendaftar)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pendaftar->dataSiswa->nama ?? $pendaftar->user->name ?? '-' }}</td>
                                <td>{{ $pendaftar->jurusan->nama ?? '-' }}</td>
                                <td>{{ $pendaftar->gelombang->nama ?? '-' }}</td>
                                <td>Rp {{ number_format($pendaftar->gelombang->biaya_daftar ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $pendaftar->created_at->format('d/m/Y') }}</td>
                                <td><span class="badge bg-warning text-dark">Menunggu Verifikasi Pembayaran</span></td>
                                <td>
                                    <a href="{{ route('keuangan.pembayaran.detail', $pendaftar->id) }}" class="btn btn-sm btn-primary me-1">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </a>
                                    <button class="btn btn-sm btn-success me-1" onclick="verifikasi({{ $pendaftar->id }}, 'PAID')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="verifikasi({{ $pendaftar->id }}, 'ADM_PASS')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada pembayaran yang menunggu verifikasi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Rekap -->
            <div class="tab-pane fade" id="rekap">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background: #e3f2fd; border-radius: 8px;">
                            <h5 style="color: #3498db;">Rp {{ number_format($totalEstimasiBayar ?? 0, 0, ',', '.') }}</h5>
                            <small class="text-muted">Total Pembayaran Masuk</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background: #e8f5e9; border-radius: 8px;">
                            <h5 style="color: #27ae60;">{{ $sudahBayar ?? 0 }}</h5>
                            <small class="text-muted">Sudah Bayar</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background: #fff3e0; border-radius: 8px;">
                            <h5 style="color: #e67e22;">Rp {{ number_format($estimasiBelumBayar ?? 0, 0, ',', '.') }}</h5>
                            <small class="text-muted">Estimasi Belum Terbayar</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3" style="background: #f3e5f5; border-radius: 8px;">
                            <h5 style="color: #9b59b6;">{{ $totalPendaftar > 0 ? round(($sudahBayar / $totalPendaftar) * 100, 1) : 0 }}%</h5>
                            <small class="text-muted">Persentase Terbayar</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Daftar -->
            <div class="tab-pane fade" id="daftar">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Jurusan</th>
                                <th>Gelombang</th>
                                <th>Biaya Daftar</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($semuaPendaftar as $index => $pendaftar)
                            <tr>
                                <td>{{ $semuaPendaftar->firstItem() + $index }}</td>
                                <td>{{ $pendaftar->dataSiswa->nama ?? $pendaftar->user->name ?? '-' }}</td>
                                <td>{{ $pendaftar->jurusan->nama ?? '-' }}</td>
                                <td>{{ $pendaftar->gelombang->nama ?? '-' }}</td>
                                <td>Rp {{ number_format($pendaftar->gelombang->biaya_daftar ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $pendaftar->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @switch($pendaftar->status)
                                        @case('SUBMIT')
                                            <span class="badge bg-secondary">Menunggu Verifikasi</span>
                                            @break
                                        @case('ADM_PASS')
                                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                                            @break
                                        @case('ADM_REJECT')
                                            <span class="badge bg-danger">Ditolak Administrasi</span>
                                            @break
                                        @case('PAYMENT_PENDING')
                                            <span class="badge bg-info">Menunggu Verifikasi Pembayaran</span>
                                            @break
                                        @case('PAID')
                                            <span class="badge bg-success">Sudah Bayar</span>
                                            @break
                                        @case('ACCEPTED')
                                            <span class="badge bg-success">Diterima</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $pendaftar->status }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Tidak ada data pendaftar</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $semuaPendaftar->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function verifikasi(id, status) {
        const aksi = status === 'PAID' ? 'menerima pembayaran' : 'menolak pembayaran';
        const icon = status === 'PAID' ? 'question' : 'warning';
        const color = status === 'PAID' ? '#27ae60' : '#e74c3c';
        
        Swal.fire({
            title: `${aksi.charAt(0).toUpperCase() + aksi.slice(1)}?`,
            text: `Apakah Anda yakin ingin ${aksi} untuk pendaftar ini?`,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: color,
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Ya, ${aksi.charAt(0).toUpperCase() + aksi.slice(1)}!`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/keuangan/verifikasi/${id}`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status';
                statusInput.value = status;
                
                form.appendChild(csrfToken);
                form.appendChild(statusInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
