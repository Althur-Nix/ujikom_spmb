@extends('keuangan.layout')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0" style="color: #2c3e50; font-weight: 600;"><i class="fas fa-receipt me-2"></i>Detail Pembayaran</h4>
    <a href="{{ route('keuangan.dashboard') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="content-card">
            <div class="card-header">
                <i class="fas fa-user me-2"></i>Informasi Pendaftar
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>No. Pendaftaran</strong></td>
                        <td>: {{ $pendaftar->no_pendaftaran }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama</strong></td>
                        <td>: {{ $pendaftar->dataSiswa->nama ?? $pendaftar->user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>: {{ $pendaftar->user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jurusan</strong></td>
                        <td>: {{ $pendaftar->jurusan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Gelombang</strong></td>
                        <td>: {{ $pendaftar->gelombang->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Biaya Pendaftaran</strong></td>
                        <td>: Rp {{ number_format($pendaftar->gelombang->biaya_daftar ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="content-card">
            <div class="card-header">
                <i class="fas fa-money-check me-2"></i>Detail Pembayaran
            </div>
            <div class="card-body">
                @if($pendaftar->pembayaran->count() > 0)
                    @php $pembayaran = $pendaftar->pembayaran->first(); @endphp
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Bank Tujuan</strong></td>
                            <td>: {{ $pembayaran->bank_tujuan }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nominal Transfer</strong></td>
                            <td>: Rp {{ number_format($pembayaran->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Transfer</strong></td>
                            <td>: {{ $pembayaran->tanggal_transfer->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Pengirim</strong></td>
                            <td>: {{ $pembayaran->nama_pengirim }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>: 
                                @switch($pembayaran->status)
                                    @case('PENDING')
                                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                        @break
                                    @case('VERIFIED')
                                        <span class="badge bg-success">Terverifikasi</span>
                                        @break
                                    @case('REJECTED')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Upload</strong></td>
                            <td>: {{ $pembayaran->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Belum ada data pembayaran
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($pendaftar->pembayaran->count() > 0)
    @php $pembayaran = $pendaftar->pembayaran->first(); @endphp
    <div class="content-card mb-3">
        <div class="card-header">
            <i class="fas fa-image me-2"></i>Bukti Transfer
        </div>
        <div class="card-body text-center">
            @php
                $fileExtension = pathinfo($pembayaran->bukti_transfer, PATHINFO_EXTENSION);
                $filePath = asset('storage/pembayaran/' . $pembayaran->bukti_transfer);
            @endphp
            
            @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                <img src="{{ $filePath }}" alt="Bukti Transfer" class="img-fluid" style="max-height: 500px; border-radius: 8px;">
            @elseif(strtolower($fileExtension) === 'pdf')
                <div class="alert alert-info">
                    <i class="fas fa-file-pdf fa-3x mb-3"></i><br>
                    <strong>File PDF</strong><br>
                    <a href="{{ $filePath }}" target="_blank" class="btn btn-primary mt-2">
                        <i class="fas fa-external-link-alt me-1"></i>Buka PDF
                    </a>
                </div>
            @endif
            
            <div class="mt-3">
                <a href="{{ $filePath }}" target="_blank" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i>Download
                </a>
            </div>
        </div>
    </div>


@endif
@endsection


