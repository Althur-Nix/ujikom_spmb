@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard Pendaftaran</h2>
        <div class="badge bg-{{ $pendaftaran->status_color ?? 'secondary' }} fs-6">
          {{ $pendaftaran->status_text ?? 'Belum Mendaftar' }}
        </div>
      </div>
    </div>
  </div>

  @if(!$pendaftaran)
  <div class="row">
    <div class="col-md-8 mx-auto">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
          <h4>Mulai Pendaftaran</h4>
          <p class="text-muted">Lengkapi formulir pendaftaran untuk melanjutkan</p>
          <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
        </div>
      </div>
    </div>
  </div>
  @else
  <div class="row mb-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Status Pendaftaran</h5>
          <div class="timeline">
            @php
            $steps = [
              'draft' => 'Formulir Pendaftaran',
              'submitted' => 'Berkas Dikirim', 
              'verified' => 'Verifikasi Admin',
              'payment_pending' => 'Menunggu Pembayaran',
              'paid' => 'Pembayaran Lunas',
              'accepted' => 'Diterima'
            ];
            @endphp
            
            @foreach($steps as $key => $label)
            <div class="timeline-item {{ $pendaftaran->status == $key ? 'active' : '' }}">
              <div class="timeline-marker"></div>
              <div class="timeline-content">
                <h6>{{ $label }}</h6>
                @if($pendaftaran->status == $key)
                <small class="text-primary">Status saat ini</small>
                @endif
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <i class="fas fa-edit text-primary me-2"></i>
            <h6 class="mb-0">Formulir Pendaftaran</h6>
          </div>
          <p class="text-muted small">Lengkapi data diri dan pilihan jurusan</p>
          <a href="{{ route('pendaftaran.edit') }}" class="btn btn-outline-primary btn-sm">
            {{ $pendaftaran->status == 'draft' ? 'Lengkapi' : 'Lihat Detail' }}
          </a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <i class="fas fa-upload text-warning me-2"></i>
            <h6 class="mb-0">Upload Berkas</h6>
          </div>
          <p class="text-muted small">Unggah ijazah, rapor, dan dokumen lainnya</p>
          <a href="{{ route('berkas.index') }}" class="btn btn-outline-warning btn-sm">Upload Berkas</a>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <i class="fas fa-credit-card text-success me-2"></i>
            <h6 class="mb-0">Pembayaran</h6>
          </div>
          <p class="text-muted small">Bayar biaya pendaftaran</p>
          <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-success btn-sm">Lihat Status</a>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>

<style>
.timeline { position: relative; padding-left: 30px; }
.timeline::before { content: ''; position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background: #e9ecef; }
.timeline-item { position: relative; margin-bottom: 20px; }
.timeline-marker { position: absolute; left: -23px; top: 5px; width: 16px; height: 16px; border-radius: 50%; background: #e9ecef; border: 3px solid white; }
.timeline-item.active .timeline-marker { background: #0d6efd; }
</style>
@endsection