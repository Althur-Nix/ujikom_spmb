@extends('layouts.pendaftar')

@section('title', 'Status Pendaftaran')

@php
    $statusInfo = \App\Helpers\StatusHelper::getStatusInfo($pendaftar);
    $timeline = \App\Helpers\StatusHelper::getTimelineStatus($pendaftar);
@endphp

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.completed .timeline-marker {
    box-shadow: 0 0 0 2px #27ae60;
}

.timeline-item.rejected .timeline-marker {
    box-shadow: 0 0 0 2px #dc3545;
}

.timeline-item.pending .timeline-marker {
    box-shadow: 0 0 0 2px #ffc107;
}

.timeline-item.waiting .timeline-marker {
    box-shadow: 0 0 0 2px #3498db;
}

.timeline-item.inactive .timeline-marker {
    box-shadow: 0 0 0 2px #6c757d;
    opacity: 0.5;
}

.timeline-item.inactive .timeline-content {
    opacity: 0.5;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
    color: #2c3e50;
}

.timeline-content p {
    margin-bottom: 0;
    font-size: 0.9rem;
    color: #7f8c8d;
}
</style>
@endpush

@section('content')
<div class="content-card">
    <div class="card-header">
        <i class="fas fa-info-circle me-2"></i>Status Pendaftaran
    </div>
    <div class="card-body">
        @if($pendaftar)
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="mb-3" style="color: #2c3e50;"><i class="fas fa-user me-2"></i>Informasi Pendaftaran</h6>
                <table class="table table-borderless">
                    <tr>
                        <td>No. Pendaftaran</td>
                        <td>: <strong>{{ $pendaftar->no_pendaftaran }}</strong></td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>: {{ $pendaftar->dataSiswa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td>: {{ $pendaftar->jurusan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Gelombang</td>
                        <td>: {{ $pendaftar->gelombang->nama ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="mb-3" style="color: #2c3e50;"><i class="fas fa-flag me-2"></i>Status Saat Ini</h6>
                <div class="alert border-0 bg-{{ $statusInfo['color'] }} text-white">
                    <h5 class="mb-0">
                        <i class="{{ $statusInfo['icon'] }}"></i> {{ $statusInfo['label'] }}
                    </h5>
                    <small>{{ $statusInfo['description'] }}</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h6 class="mb-3" style="color: #2c3e50;"><i class="fas fa-stream me-2"></i>Timeline Proses</h6>
                <div class="timeline">
                    <!-- Pendaftaran -->
                    <div class="timeline-item {{ $timeline['pendaftaran']['completed'] ? 'completed' : 'inactive' }}">
                        <div class="timeline-marker {{ $timeline['pendaftaran']['completed'] ? 'bg-success' : 'bg-secondary' }}"></div>
                        <div class="timeline-content">
                            <h6>{{ $timeline['pendaftaran']['label'] }}</h6>
                            <p>{{ $timeline['pendaftaran']['description'] }}</p>
                        </div>
                    </div>
                    
                    <!-- Upload Berkas -->
                    <div class="timeline-item {{ $timeline['upload_berkas']['completed'] ? 'completed' : ($timeline['upload_berkas']['pending'] ? 'pending' : 'inactive') }}">
                        <div class="timeline-marker {{ $timeline['upload_berkas']['completed'] ? 'bg-success' : ($timeline['upload_berkas']['pending'] ? 'bg-warning' : 'bg-secondary') }}"></div>
                        <div class="timeline-content">
                            <h6>{{ $timeline['upload_berkas']['label'] }}</h6>
                            <p>{{ $timeline['upload_berkas']['description'] }}</p>
                        </div>
                    </div>
                    
                    <!-- Verifikasi Berkas -->
                    <div class="timeline-item {{ $timeline['verifikasi_berkas']['completed'] ? 'completed' : ($timeline['verifikasi_berkas']['rejected'] ? 'rejected' : ($timeline['verifikasi_berkas']['pending'] ? 'pending' : 'inactive')) }}">
                        <div class="timeline-marker {{ $timeline['verifikasi_berkas']['completed'] ? 'bg-success' : ($timeline['verifikasi_berkas']['rejected'] ? 'bg-danger' : ($timeline['verifikasi_berkas']['pending'] ? 'bg-warning' : 'bg-secondary')) }}"></div>
                        <div class="timeline-content">
                            <h6>{{ $timeline['verifikasi_berkas']['label'] }}</h6>
                            <p>{{ $timeline['verifikasi_berkas']['description'] }}</p>
                        </div>
                    </div>
                    
                    <!-- Pembayaran -->
                    <div class="timeline-item {{ $timeline['pembayaran']['completed'] ? 'completed' : ($timeline['pembayaran']['pending'] ? 'pending' : ($timeline['pembayaran']['waiting'] ? 'waiting' : 'inactive')) }}">
                        <div class="timeline-marker {{ $timeline['pembayaran']['completed'] ? 'bg-success' : ($timeline['pembayaran']['pending'] ? 'bg-warning' : ($timeline['pembayaran']['waiting'] ? 'bg-info' : 'bg-secondary')) }}"></div>
                        <div class="timeline-content">
                            <h6>{{ $timeline['pembayaran']['label'] }}</h6>
                            <p>{{ $timeline['pembayaran']['description'] }}</p>
                        </div>
                    </div>
                    
                    <!-- Seleksi Final -->
                    <div class="timeline-item {{ $timeline['seleksi_final']['completed'] ? 'completed' : ($timeline['seleksi_final']['rejected'] ? 'rejected' : ($timeline['seleksi_final']['pending'] ? 'pending' : 'inactive')) }}">
                        <div class="timeline-marker {{ $timeline['seleksi_final']['completed'] ? 'bg-success' : ($timeline['seleksi_final']['rejected'] ? 'bg-danger' : ($timeline['seleksi_final']['pending'] ? 'bg-warning' : 'bg-secondary')) }}"></div>
                        <div class="timeline-content">
                            <h6>{{ $timeline['seleksi_final']['label'] }}</h6>
                            <p>{{ $timeline['seleksi_final']['description'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($statusInfo['status'] == 'PENDING_UPLOAD')
        <div class="alert border-0 mt-4" style="background: #e3f2fd; color: #3498db;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p>{{ $statusInfo['description'] }}. Silakan upload berkas-berkas yang diperlukan.</p>
            <a href="{{ route('pendaftar.berkas') }}" class="btn btn-primary"><i class="fas fa-upload me-1"></i>Upload Berkas</a>
        </div>
        @elseif($statusInfo['status'] == 'REVISION_REQUIRED')
        <div class="alert border-0 mt-4" style="background: #fff3e0; color: #e67e22;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p>{{ $statusInfo['description'] }}. Periksa berkas yang perlu diperbaiki.</p>
            <a href="{{ route('pendaftar.berkas') }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i>Perbaiki Berkas</a>
        </div>
        @elseif($statusInfo['status'] == 'SUBMIT')
        <div class="alert border-0 mt-4" style="background: #fff3e0; color: #e67e22;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p class="mb-0">{{ $statusInfo['description'] }}. Silakan tunggu konfirmasi.</p>
        </div>
        @elseif($statusInfo['status'] == 'ADM_PASS')
        <div class="alert border-0 mt-4" style="background: #e3f2fd; color: #3498db;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p>{{ $statusInfo['description'] }} untuk menyelesaikan proses pendaftaran.</p>
            <a href="{{ route('pendaftar.pembayaran') }}" class="btn btn-primary"><i class="fas fa-credit-card me-1"></i>Lakukan Pembayaran</a>
        </div>
        @elseif($statusInfo['status'] == 'ADM_REJECT')
        <div class="alert border-0 mt-4" style="background: #ffebee; color: #dc3545;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p class="mb-0">{{ $statusInfo['description'] }}. Silakan hubungi admin untuk informasi lebih lanjut.</p>
        </div>
        @elseif($statusInfo['status'] == 'PAYMENT_PENDING')
        <div class="alert border-0 mt-4" style="background: #fff3e0; color: #e67e22;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p class="mb-0">{{ $statusInfo['description'] }}. Silakan tunggu konfirmasi.</p>
        </div>
        @elseif($statusInfo['status'] == 'PAID')
        <div class="alert border-0 mt-4" style="background: #e8f5e9; color: #27ae60;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p class="mb-0">{{ $statusInfo['description'] }}. Silakan tunggu informasi seleksi final dari sekolah.</p>
        </div>
        @elseif($statusInfo['status'] == 'ACCEPTED')
        <div class="alert border-0 mt-4" style="background: #e8f5e9; color: #27ae60;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p class="mb-0">{{ $statusInfo['description'] }}. Selamat atas pencapaian Anda!</p>
        </div>
        @elseif($statusInfo['status'] == 'REJECTED')
        <div class="alert border-0 mt-4" style="background: #ffebee; color: #dc3545;">
            <h6><i class="{{ $statusInfo['icon'] }} me-2"></i>{{ $statusInfo['label'] }}</h6>
            <p class="mb-0">{{ $statusInfo['description'] }}. Jangan menyerah, coba lagi tahun depan!</p>
        </div>
        @endif
        @else
        <div class="alert border-0" style="background: #fff3e0; color: #e67e22;">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Data Tidak Ditemukan</h6>
            <p>Anda belum melakukan pendaftaran. Silakan lengkapi data pendaftaran terlebih dahulu.</p>
            <a href="{{ route('pendaftar.form') }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Isi Form Pendaftaran</a>
        </div>
        @endif
    </div>
</div>
@endsection
