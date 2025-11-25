@extends('layouts.admin')

@section('content')
<!-- Statistik Utama -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-box blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['jurusan'] }}</p>
                    <p class="stat-label">Jurusan</p>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['gelombang'] }}</p>
                    <p class="stat-label">Gelombang</p>
                </div>
                <div class="stat-icon green">
                    <i class="fas fa-calendar"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['total_pendaftar'] }}</p>
                    <p class="stat-label">Total Pendaftar</p>
                </div>
                <div class="stat-icon orange">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box purple">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['users'] }}</p>
                    <p class="stat-label">Pengguna</p>
                </div>
                <div class="stat-icon purple">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Pendaftar -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-box success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['diterima'] ?? 0 }}</p>
                    <p class="stat-label">Diterima</p>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="stat-label">Menunggu Verifikasi</p>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['verified'] ?? 0 }}</p>
                    <p class="stat-label">Terverifikasi</p>
                </div>
                <div class="stat-icon info">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['ditolak'] ?? 0 }}</p>
                    <p class="stat-label">Ditolak</p>
                </div>
                <div class="stat-icon danger">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Sistem -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="content-card">
            <div class="card-header">
                <i class="fas fa-chart-pie me-2"></i>Ringkasan Pendaftaran
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center p-3">
                            <h4 class="text-success">{{ number_format((($stats['diterima'] ?? 0) / max($stats['total_pendaftar'], 1)) * 100, 1) }}%</h4>
                            <small class="text-muted">Tingkat Penerimaan</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3">
                            <h4 class="text-info">{{ number_format((($stats['verified'] ?? 0) / max($stats['total_pendaftar'], 1)) * 100, 1) }}%</h4>
                            <small class="text-muted">Sudah Terverifikasi</small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="progress mb-2" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ ($stats['diterima'] ?? 0) / max($stats['total_pendaftar'], 1) * 100 }}%"></div>
                    <div class="progress-bar bg-info" style="width: {{ ($stats['verified'] ?? 0) / max($stats['total_pendaftar'], 1) * 100 }}%"></div>
                    <div class="progress-bar bg-warning" style="width: {{ ($stats['pending'] ?? 0) / max($stats['total_pendaftar'], 1) * 100 }}%"></div>
                    <div class="progress-bar bg-danger" style="width: {{ ($stats['ditolak'] ?? 0) / max($stats['total_pendaftar'], 1) * 100 }}%"></div>
                </div>
                <small class="text-muted">Status distribusi pendaftar</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="content-card">
            <div class="card-header">
                <i class="fas fa-cogs me-2"></i>Informasi Sistem
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="p-2">
                            <i class="fas fa-database fa-2x text-primary mb-2"></i>
                            <h6>Database</h6>
                            <small class="text-success">Aktif</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2">
                            <i class="fas fa-server fa-2x text-info mb-2"></i>
                            <h6>Server</h6>
                            <small class="text-success">Online</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2">
                            <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                            <h6>Keamanan</h6>
                            <small class="text-success">Aman</small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <small class="text-muted">Sistem SPMB v1.0 - {{ date('Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Pembayaran -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stat-box info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['menunggu_pembayaran'] ?? 0 }}</p>
                    <p class="stat-label">Menunggu Pembayaran</p>
                </div>
                <div class="stat-icon info">
                    <i class="fas fa-credit-card"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-box warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['menunggu_verifikasi_pembayaran'] ?? 0 }}</p>
                    <p class="stat-label">Menunggu Verifikasi Bayar</p>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-box success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="stat-number">{{ $stats['sudah_bayar'] ?? 0 }}</p>
                    <p class="stat-label">Sudah Bayar</p>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-money-check-alt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="content-card">
    <div class="card-header">
        <i class="fas fa-history me-2"></i>Ringkasan Proses Pendaftaran
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="alert alert-info">
                    <i class="fas fa-user-plus me-2"></i>
                    <strong>Total Pendaftar</strong><br>
                    <small>{{ $stats['total_pendaftar'] }} orang terdaftar</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-warning">
                    <i class="fas fa-file-alt me-2"></i>
                    <strong>Verifikasi Berkas</strong><br>
                    <small>{{ $stats['pending'] ?? 0 }} menunggu verifikasi panitia</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-primary">
                    <i class="fas fa-credit-card me-2"></i>
                    <strong>Proses Pembayaran</strong><br>
                    <small>{{ ($stats['menunggu_pembayaran'] ?? 0) + ($stats['menunggu_verifikasi_pembayaran'] ?? 0) }} dalam proses bayar</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Selesai</strong><br>
                    <small>{{ $stats['diterima'] ?? 0 }} diterima resmi</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection