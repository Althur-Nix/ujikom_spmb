@extends('panitia.layout')

@section('title', 'Detail Verifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0" style="color: #2c3e50; font-weight: 600;"><i class="fas fa-file-alt me-2"></i>Detail Verifikasi Berkas</h4>
    <a href="{{ route('panitia.verifikasi') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="content-card mb-3">
    <div class="card-header">
        <i class="fas fa-user me-2"></i>Data Pendaftar
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <td width="150"><strong>Nama Lengkap</strong></td>
                        <td>: {{ $pendaftar->dataSiswa->nama ?? $pendaftar->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>: {{ $pendaftar->user->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jurusan</strong></td>
                        <td>: {{ $pendaftar->jurusan->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>: 
                            @switch($pendaftar->status)
                                @case('DRAFT')
                                    <span class="badge bg-secondary">Belum Upload Berkas</span>
                                    @break
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
                                    <span class="badge bg-secondary">{{ $pendaftar->status }}</span>
                            @endswitch
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="content-card mb-3">
    <div class="card-header">
        <i class="fas fa-folder-open me-2"></i>Berkas Pendaftar
    </div>
    <div class="card-body">
        @if($pendaftar->berkas && $pendaftar->berkas->where('is_draft', false)->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jenis Berkas</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendaftar->berkas->where('is_draft', false) as $berkas)
                        <tr>
                            <td>{{ $berkas->jenis }}</td>
                            <td>
                                {{ $berkas->nama_file }}
                                @if($berkas->catatan)
                                    <br><small class="text-danger"><i class="fas fa-info-circle"></i> {{ $berkas->catatan }}</small>
                                @endif
                            </td>
                            <td>
                                @if($berkas->valid)
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($berkas->catatan)
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary me-1" onclick="previewBerkas('{{ asset('storage/' . $berkas->url) }}', '{{ $berkas->nama_file }}')">
                                    <i class="fas fa-eye me-1"></i>Lihat
                                </button>
                                @if(!$berkas->valid)
                                    <button class="btn btn-sm btn-success me-1" onclick="verifikasiBerkas({{ $berkas->id }}, 'valid')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="verifikasiBerkas({{ $berkas->id }}, 'invalid')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x mb-3" style="color: #bdc3c7;"></i>
                <h5 style="color: #7f8c8d;">Belum ada berkas yang diupload</h5>
            </div>
        @endif
    </div>
</div>

@if(in_array($pendaftar->status, ['SUBMIT', 'REVISION_REQUIRED']))
<div class="content-card">
    <div class="card-header">
        <i class="fas fa-tasks me-2"></i>Aksi Verifikasi
    </div>
    <div class="card-body">
        @php
            $submittedBerkas = $pendaftar->berkas->where('is_draft', false);
            $allBerkasValid = $submittedBerkas->count() > 0 && $submittedBerkas->every(function($berkas) {
                return $berkas->valid;
            });
        @endphp
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-success btn-lg w-100 {{ !$allBerkasValid ? 'disabled' : '' }}" 
                        onclick="updateStatus('ADM_PASS')" 
                        {{ !$allBerkasValid ? 'disabled' : '' }}>
                    <i class="fas fa-check me-2"></i>Terima Berkas
                </button>
                @if(!$allBerkasValid)
                    <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i>Semua berkas harus diverifikasi terlebih dahulu</small>
                @endif
            </div>
            <div class="col-md-6">
                <button class="btn btn-danger btn-lg w-100" onclick="updateStatus('ADM_REJECT')">
                    <i class="fas fa-times me-2"></i>Tolak Berkas
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<form id="verifikasi-form" action="{{ route('panitia.verifikasi.update-status', $pendaftar->id) }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="status" id="status-input">
</form>

<form id="berkas-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="status" id="berkas-status-input">
    <input type="hidden" name="catatan" id="berkas-catatan-input">
</form>

<!-- Modal Preview Berkas -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewTitle">Preview Berkas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="previewContent"></div>
            </div>
            <div class="modal-footer">
                <a id="downloadLink" href="#" target="_blank" class="btn btn-primary">
                    <i class="fas fa-download me-1"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Catatan Revisi -->
<div class="modal fade" id="catatanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alasan Penolakan Berkas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="catatan-textarea" rows="3" placeholder="Masukkan alasan penolakan (opsional)"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="submitTolakBerkas()">Tolak Berkas</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentBerkasId = null;

    function previewBerkas(url, filename) {
        $('#previewTitle').text('Preview: ' + filename);
        $('#downloadLink').attr('href', url);
        
        const fileExt = filename.split('.').pop().toLowerCase();
        let content = '';
        
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            content = `<img src="${url}" class="img-fluid" style="max-height: 500px;">`;
        } else if (fileExt === 'pdf') {
            content = `<embed src="${url}" type="application/pdf" width="100%" height="500px">`;
        } else {
            content = `<p class="text-muted">Preview tidak tersedia untuk file ini.<br>Klik download untuk melihat file.</p>`;
        }
        
        $('#previewContent').html(content);
        $('#previewModal').modal('show');
    }

    function updateStatus(status) {
        const aksi = status === 'ADM_PASS' ? 'menerima' : 'menolak';
        const icon = status === 'ADM_PASS' ? 'question' : 'warning';
        const color = status === 'ADM_PASS' ? '#27ae60' : '#e74c3c';
        
        Swal.fire({
            title: `${aksi.charAt(0).toUpperCase() + aksi.slice(1)} Berkas?`,
            text: `Apakah Anda yakin ingin ${aksi} berkas pendaftar ini?`,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: color,
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Ya, ${aksi.charAt(0).toUpperCase() + aksi.slice(1)}!`,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('status-input').value = status;
                document.getElementById('verifikasi-form').submit();
            }
        });
    }

    function verifikasiBerkas(berkasId, status) {
        if (status === 'valid') {
            Swal.fire({
                title: 'Terima Berkas?',
                text: 'Apakah Anda yakin ingin menerima berkas ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#27ae60',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('berkas-form');
                    form.action = `/panitia/berkas/${berkasId}/verifikasi`;
                    document.getElementById('berkas-status-input').value = status;
                    document.getElementById('berkas-catatan-input').value = '';
                    form.submit();
                }
            });
        } else {
            currentBerkasId = berkasId;
            const modal = new bootstrap.Modal(document.getElementById('catatanModal'));
            modal.show();
        }
    }

    function submitTolakBerkas() {
        const catatan = document.getElementById('catatan-textarea').value;
        const form = document.getElementById('berkas-form');
        form.action = `/panitia/berkas/${currentBerkasId}/verifikasi`;
        document.getElementById('berkas-status-input').value = 'invalid';
        document.getElementById('berkas-catatan-input').value = catatan;
        form.submit();
    }
</script>
@endpush
@endsection