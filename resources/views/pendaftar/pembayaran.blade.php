@extends('layouts.pendaftar')

@section('title', 'Pembayaran')

@section('content')
<div class="content-card">
    <div class="card-header">
        <i class="fas fa-credit-card me-2"></i>Pembayaran
    </div>
    <div class="card-body">
        @if($pendaftar && $pendaftar->status == 'ADM_PASS')
        <div class="alert border-0 mb-4" style="background: #e8f5e9; color: #27ae60;">
            <h6><i class="fas fa-check-circle me-2"></i>Selamat!</h6>
            <p class="mb-0">Anda telah lolos verifikasi berkas. Silakan lakukan pembayaran untuk melanjutkan ke tahap seleksi final.</p>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="content-card">
                    <div class="card-header" style="background: #3498db; color: white; border-bottom: none;">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pembayaran
                    </div>
                    <div class="card-body">
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
                                <td><strong>Biaya Pendaftaran</strong></td>
                                <td>: <strong style="color: #e67e22;">Rp {{ number_format($pendaftar->gelombang->biaya_daftar ?? 0, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="content-card">
                    <div class="card-header" style="background: #27ae60; color: white; border-bottom: none;">
                        <i class="fas fa-university me-2"></i>Rekening Pembayaran
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Bank BRI</strong><br>
                            No. Rek: 1234-5678-9012-3456<br>
                            A.n: SMK BAKTI NUSANTARA 666
                        </div>
                        <div class="mb-3">
                            <strong>Bank Mandiri</strong><br>
                            No. Rek: 9876-5432-1098-7654<br>
                            A.n: SMK BAKTI NUSANTARA 666
                        </div>
                        <div class="alert border-0" style="background: #fff3e0; color: #e67e22;">
                            <small><i class="fas fa-exclamation-triangle me-1"></i>Pastikan nominal transfer sesuai dengan biaya pendaftaran</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card mt-3">
            <div class="card-header">
                <i class="fas fa-upload me-2"></i>Upload Bukti Pembayaran
            </div>
            <div class="card-body">
                <form id="payment-form" method="POST" action="{{ route('pendaftar.pembayaran.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Bank Tujuan <span class="text-danger">*</span></label>
                                <select name="bank_tujuan" class="form-select" required>
                                    <option value="">Pilih Bank</option>
                                    <option value="BRI">Bank BRI</option>
                                    <option value="Mandiri">Bank Mandiri</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nominal Transfer</label>
                                <input type="text" name="nominal_display" class="form-control" value="Rp {{ number_format($pendaftar->gelombang->biaya_daftar ?? 0, 0, ',', '.') }}" readonly>
                                <input type="hidden" name="nominal" value="{{ $pendaftar->gelombang->biaya_daftar ?? 0 }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Transfer <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_transfer" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Pengirim <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pengirim" class="form-control" placeholder="Nama sesuai rekening pengirim" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Transfer <span class="text-danger">*</span></label>
                        <input type="file" name="bukti_transfer" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                        <small class="text-muted">Upload foto/scan bukti transfer (JPG, PNG, PDF - Max 2MB)</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('pendaftar.status') }}" class="btn btn-secondary">Kembali</a>
                        <button type="button" class="btn btn-primary" onclick="confirmUploadBukti()"><i class="fas fa-upload me-1"></i>Upload Bukti Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>

        @elseif($pendaftar && $pendaftar->status == 'PAYMENT_PENDING')
        <div class="alert border-0" style="background: #fff3e0; color: #e67e22;">
            <h6><i class="fas fa-hourglass-half me-2"></i>Menunggu Verifikasi Pembayaran</h6>
            <p class="mb-0">Bukti pembayaran Anda sudah diupload dan sedang diverifikasi oleh bagian keuangan. Silakan tunggu konfirmasi.</p>
        </div>
        
        <div class="content-card">
            <div class="card-header">
                <i class="fas fa-info-circle me-2"></i>Status Pembayaran
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Status</td>
                        <td>: <span class="badge bg-warning text-dark">Menunggu Verifikasi</span></td>
                    </tr>
                    <tr>
                        <td>Tanggal Upload</td>
                        <td>: {{ $pendaftar->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @elseif($pendaftar && $pendaftar->status == 'ACCEPTED')
        <div class="alert border-0" style="background: #e8f5e9; color: #27ae60;">
            <h6><i class="fas fa-trophy me-2"></i>SELAMAT! Anda DITERIMA</h6>
            <p class="mb-0">Selamat! Anda telah diterima di SMK BAKTI NUSANTARA 666. Silakan tunggu informasi lebih lanjut dari sekolah.</p>
        </div>
        
        <div class="content-card">
            <div class="card-header" style="background: #27ae60; color: white; border-bottom: none;">
                <i class="fas fa-check-circle me-2"></i>Status Penerimaan
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Status Penerimaan</td>
                        <td>: <span class="badge bg-success fs-6">DITERIMA</span></td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran</td>
                        <td>: <span class="badge bg-success">Lunas</span></td>
                    </tr>
                    <tr>
                        <td>Nominal</td>
                        <td>: Rp {{ number_format($pendaftar->gelombang->biaya_daftar ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Diterima</td>
                        <td>: {{ $pendaftar->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @elseif($pendaftar && in_array($pendaftar->status, ['PAID', 'REJECTED']))
        <div class="alert border-0" style="background: #e8f5e9; color: #27ae60;">
            <h6><i class="fas fa-check-circle me-2"></i>Pembayaran Berhasil</h6>
            <p class="mb-0">Pembayaran Anda telah dikonfirmasi dan diverifikasi oleh admin.</p>
        </div>
        
        @if($pendaftar->status == 'PAID')
        <div class="alert border-0" style="background: #e3f2fd; color: #3498db;">
            <h6><i class="fas fa-hourglass-half me-2"></i>Tahap Selanjutnya</h6>
            <p class="mb-0">Anda sedang dalam proses seleksi final. Pengumuman hasil akan diinformasikan melalui sistem dan kontak yang terdaftar.</p>
        </div>
        @elseif($pendaftar->status == 'REJECTED')
        <div class="alert border-0" style="background: #ffebee; color: #dc3545;">
            <h6><i class="fas fa-times-circle me-2"></i>Hasil Seleksi</h6>
            <p class="mb-0">Mohon maaf, Anda belum berhasil dalam seleksi kali ini. Terima kasih atas partisipasinya.</p>
        </div>
        @endif

        <div class="content-card">
            <div class="card-header">
                <i class="fas fa-file-invoice-dollar me-2"></i>Detail Pembayaran
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td>Status Pembayaran</td>
                        <td>: <span class="badge bg-success">Lunas</span></td>
                    </tr>
                    <tr>
                        <td>Status Pendaftaran</td>
                        <td>: 
                            <span class="badge bg-{{ $pendaftar->status == 'PAID' ? 'primary' : ($pendaftar->status == 'ACCEPTED' ? 'success' : 'danger') }}">
                                @if($pendaftar->status == 'PAID')
                                    Menunggu Seleksi
                                @elseif($pendaftar->status == 'ACCEPTED')
                                    DITERIMA
                                @elseif($pendaftar->status == 'REJECTED')
                                    DITOLAK
                                @endif
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Nominal</td>
                        <td>: Rp {{ number_format($pendaftar->gelombang->biaya_daftar ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Upload Bukti</td>
                        <td>: {{ $pendaftar->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @elseif($pendaftar && $pendaftar->status == 'SUBMIT')
        <div class="alert border-0" style="background: #fff3e0; color: #e67e22;">
            <h6><i class="fas fa-clock me-2"></i>Menunggu Verifikasi</h6>
            <p class="mb-0">Pendaftaran Anda masih dalam proses verifikasi panitia. Pembayaran dapat dilakukan setelah lolos verifikasi.</p>
        </div>

        @elseif($pendaftar && $pendaftar->status == 'ADM_REJECT')
        <div class="alert border-0" style="background: #ffebee; color: #dc3545;">
            <h6><i class="fas fa-times-circle me-2"></i>Tidak Dapat Melakukan Pembayaran</h6>
            <p class="mb-0">Pendaftaran Anda tidak lolos verifikasi administrasi. Silakan hubungi admin untuk informasi lebih lanjut.</p>
        </div>

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

@push('scripts')
<script>
function confirmUploadBukti() {
    const form = document.getElementById('payment-form');
    console.log('Form found:', form);
    console.log('Form action:', form?.action);
    console.log('Form method:', form?.method);
    
    Swal.fire({
        title: 'Upload Bukti Pembayaran?',
        text: 'Pastikan semua data sudah benar. Setelah diupload, data tidak dapat diubah lagi.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Upload!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            if (form) {
                console.log('Submitting form...');
                form.submit();
            } else {
                console.error('Form not found');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Form tidak ditemukan!',
                    confirmButtonColor: '#28a745'
                });
            }
        }
    });
}
</script>
@endpush
