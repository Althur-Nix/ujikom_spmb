@extends('layouts.pendaftar')

@section('title', 'Upload Berkas')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
@php
    $user = session('user');
    $pendaftar = \App\Models\Pendaftar::where('user_id', $user['id'])->first();
@endphp

@if($pendaftar && $pendaftar->status === 'ADM_PASS')
<div class="content-card">
    <div class="card-header bg-success text-white">
        <i class="fas fa-check-circle me-2"></i>Berkas Diterima
    </div>
    <div class="card-body text-center py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
        <h3 class="text-success mb-3">Selamat! Berkas Anda Telah Diterima</h3>
        <p class="lead mb-4">Berkas pendaftaran Anda telah diverifikasi dan diterima oleh panitia.</p>
        <div class="alert alert-info mb-4">
            <h5><i class="fas fa-credit-card me-2"></i>Langkah Selanjutnya: Pembayaran</h5>
            <p class="mb-0">Silakan lakukan pembayaran biaya pendaftaran untuk melanjutkan proses pendaftaran Anda.</p>
        </div>
        <a href="{{ route('pendaftar.pembayaran') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran
        </a>
        <a href="{{ route('pendaftar.dashboard') }}" class="btn btn-outline-secondary btn-lg ms-2">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>
@else
<div class="content-card">
    <div class="card-header">
        <i class="fas fa-upload me-2"></i>Upload Berkas
    </div>
    <div class="card-body">
        <div class="alert border-0" style="background: #e3f2fd; color: #3498db;">
            <h6><i class="fas fa-info-circle me-2"></i>Persyaratan Berkas:</h6>
            <ul class="mb-0">
                <li>Format file: PDF atau JPG/PNG</li>
                <li>Ukuran maksimal: 2MB per file</li>
                <li>Pastikan file dapat dibaca dengan jelas</li>
            </ul>
        </div>

        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
        @endif
        
        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#28a745'
                });
            });
        </script>
        @endif
        
        <div class="alert border-0 mb-3" style="background: #fff3e0; color: #e67e22;">
            <h6><i class="fas fa-info-circle me-2"></i>Berkas yang Diperlukan:</h6>
            <p class="mb-0">Upload berkas: <strong>Ijazah/Rapor/KIP/KKS/Akta/KK</strong> (format: PDF/JPG, ukuran dibatasi)</p>
            <small class="text-info"><i class="fas fa-save me-1"></i>Berkas tersimpan otomatis sebagai draft, klik Upload Berkas untuk mengirim ke panitia</small>
        </div>
        
        <div class="alert alert-warning border-0 mb-4">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Berkas Wajib:</h6>
            <p class="mb-0">Anda <strong>WAJIB</strong> mengupload 4 berkas berikut: <strong>Ijazah, Rapor, Akta Kelahiran, dan Kartu Keluarga (KK)</strong></p>
            <small class="text-muted">Berkas KIP dan KKS bersifat opsional (jika ada)</small>
        </div>
        
        <form id="berkas-form" action="{{ route('pendaftar.berkas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="finalize_submission" value="true">
            <div class="row">
                <div class="col-md-6 mb-3 required-field">
                    <label class="form-label">Ijazah <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="file" name="ijazah" class="form-control auto-save" data-type="ijazah" accept=".pdf,.jpg,.jpeg,.png" {{ in_array('ijazah', $draftTypes) || $uploadedBerkas->where('jenis', 'IJAZAH')->count() > 0 ? 'disabled' : '' }}>
                        <div class="draft-status d-none">
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Tersimpan sebagai draft</small>
                        </div>
                    </div>
                    @if(in_array('ijazah', $draftTypes))
                        <small class="text-warning small-text"><i class="fas fa-info-circle me-1"></i>Sudah ada di draft, hapus draft untuk upload ulang</small>
                    @elseif($uploadedBerkas->where('jenis', 'IJAZAH')->count() > 0)
                        <small class="text-success small-text"><i class="fas fa-check-circle me-1"></i>Sudah dikirim ke panitia</small>
                    @else
                        <small class="text-muted small-text">Upload ijazah SMP/MTs (PDF/JPG)</small>
                    @endif
                </div>
            
                <div class="col-md-6 mb-3 required-field">
                    <label class="form-label">Rapor <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="file" name="rapor" class="form-control auto-save" data-type="rapor" accept=".pdf,.jpg,.jpeg,.png" {{ in_array('rapor', $draftTypes) || $uploadedBerkas->where('jenis', 'RAPOR')->count() > 0 ? 'disabled' : '' }}>
                        <div class="draft-status d-none">
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Tersimpan sebagai draft</small>
                        </div>
                    </div>
                    @if(in_array('rapor', $draftTypes))
                        <small class="text-warning small-text"><i class="fas fa-info-circle me-1"></i>Sudah ada di draft, hapus draft untuk upload ulang</small>
                    @elseif($uploadedBerkas->where('jenis', 'RAPOR')->count() > 0)
                        <small class="text-success small-text"><i class="fas fa-check-circle me-1"></i>Sudah dikirim ke panitia</small>
                    @else
                        <small class="text-muted small-text">Rapor semester terakhir (PDF/JPG)</small>
                    @endif
                </div>
            
                <div class="col-md-6 mb-3">
                    <label class="form-label">KIP</label>
                    <div class="position-relative">
                        <input type="file" name="kip" class="form-control auto-save" data-type="kip" accept=".pdf,.jpg,.jpeg,.png" {{ in_array('kip', $draftTypes) || $uploadedBerkas->where('jenis', 'KIP')->count() > 0 ? 'disabled' : '' }}>
                        <div class="draft-status d-none">
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Tersimpan sebagai draft</small>
                        </div>
                    </div>
                    @if(in_array('kip', $draftTypes))
                        <small class="text-warning small-text"><i class="fas fa-info-circle me-1"></i>Sudah ada di draft, hapus draft untuk upload ulang</small>
                    @elseif($uploadedBerkas->where('jenis', 'KIP')->count() > 0)
                        <small class="text-success small-text"><i class="fas fa-check-circle me-1"></i>Sudah dikirim ke panitia</small>
                    @else
                        <small class="text-muted small-text">Kartu Indonesia Pintar (jika ada)</small>
                    @endif
                </div>
            
                <div class="col-md-6 mb-3">
                    <label class="form-label">KKS</label>
                    <div class="position-relative">
                        <input type="file" name="kks" class="form-control auto-save" data-type="kks" accept=".pdf,.jpg,.jpeg,.png" {{ in_array('kks', $draftTypes) || $uploadedBerkas->where('jenis', 'KKS')->count() > 0 ? 'disabled' : '' }}>
                        <div class="draft-status d-none">
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Tersimpan sebagai draft</small>
                        </div>
                    </div>
                    @if(in_array('kks', $draftTypes))
                        <small class="text-warning small-text"><i class="fas fa-info-circle me-1"></i>Sudah ada di draft, hapus draft untuk upload ulang</small>
                    @elseif($uploadedBerkas->where('jenis', 'KKS')->count() > 0)
                        <small class="text-success small-text"><i class="fas fa-check-circle me-1"></i>Sudah dikirim ke panitia</small>
                    @else
                        <small class="text-muted small-text">Kartu Keluarga Sejahtera (jika ada)</small>
                    @endif
                </div>
            
                <div class="col-md-6 mb-3 required-field">
                    <label class="form-label">Akta Kelahiran <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="file" name="akta" class="form-control auto-save" data-type="akta" accept=".pdf,.jpg,.jpeg,.png" {{ in_array('akta', $draftTypes) || $uploadedBerkas->where('jenis', 'AKTA')->count() > 0 ? 'disabled' : '' }}>
                        <div class="draft-status d-none">
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Tersimpan sebagai draft</small>
                        </div>
                    </div>
                    @if(in_array('akta', $draftTypes))
                        <small class="text-warning small-text"><i class="fas fa-info-circle me-1"></i>Sudah ada di draft, hapus draft untuk upload ulang</small>
                    @elseif($uploadedBerkas->where('jenis', 'AKTA')->count() > 0)
                        <small class="text-success small-text"><i class="fas fa-check-circle me-1"></i>Sudah dikirim ke panitia</small>
                    @else
                        <small class="text-muted small-text">Akta kelahiran (PDF/JPG)</small>
                    @endif
                </div>
            
                <div class="col-md-6 mb-3 required-field">
                    <label class="form-label">Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="file" name="kk" class="form-control auto-save" data-type="kk" accept=".pdf,.jpg,.jpeg,.png" {{ in_array('kk', $draftTypes) || $uploadedBerkas->where('jenis', 'KK')->count() > 0 ? 'disabled' : '' }}>
                        <div class="draft-status d-none">
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>Tersimpan sebagai draft</small>
                        </div>
                    </div>
                    @if(in_array('kk', $draftTypes))
                        <small class="text-warning small-text"><i class="fas fa-info-circle me-1"></i>Sudah ada di draft, hapus draft untuk upload ulang</small>
                    @elseif($uploadedBerkas->where('jenis', 'KK')->count() > 0)
                        <small class="text-success small-text"><i class="fas fa-check-circle me-1"></i>Sudah dikirim ke panitia</small>
                    @else
                        <small class="text-muted small-text">Kartu Keluarga (PDF/JPG)</small>
                    @endif
                </div>
            </div>
        
        
            <hr class="my-4">
            
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3"><i class="fas fa-draft2digital me-2"></i>Draft Berkas (Belum Dikirim)</h6>
                    <div id="draft-files" class="mb-3">
                        @if($draftBerkas->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($draftBerkas as $draft)
                                <div class="list-group-item p-2">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-2">
                                            <input type="checkbox" name="draft_berkas[]" value="{{ $draft->id }}" class="form-check-input" id="draft_{{ $draft->id }}">
                                        </div>
                                        <div class="flex-grow-1">
                                            <label for="draft_{{ $draft->id }}" class="mb-0 cursor-pointer">
                                                <strong>{{ strtoupper($draft->jenis) }}</strong><br>
                                                <small class="text-muted">{{ $draft->nama_file }} ({{ $draft->ukuran_kb }} KB)</small>
                                            </label>
                                        </div>
                                        <div>
                                            <span class="badge bg-info me-2">Draft</span>
                                            <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="previewBerkas('{{ asset('storage/' . $draft->url) }}', '{{ $draft->nama_file }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-draft" data-id="{{ $draft->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Centang berkas yang ingin dikirim ke panitia</small>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="select-all-drafts">Pilih Semua</button>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">Belum ada berkas draft</p>
                                <small class="text-muted">Upload berkas di atas untuk membuat draft</small>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h6 class="mb-3"><i class="fas fa-file-alt me-2"></i>Berkas yang Sudah Dikirim ke Panitia</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Jenis</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($uploadedBerkas as $berkas)
                                <tr class="{{ !$berkas->valid && $berkas->catatan ? 'table-warning' : '' }}">
                                    <td>
                                        <strong>{{ strtoupper($berkas->jenis) }}</strong><br>
                                        <small class="text-muted">{{ $berkas->nama_file }}</small>
                                        @if($berkas->catatan)
                                            <br><small class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $berkas->catatan }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($berkas->valid === true)
                                            <span class="badge bg-success">Terverifikasi</span>
                                        @elseif($berkas->valid === false && $berkas->catatan)
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="previewBerkas('{{ asset('storage/' . $berkas->url) }}', '{{ $berkas->nama_file }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($berkas->valid === false && $berkas->catatan)
                                            <button type="button" class="btn btn-sm btn-warning" onclick="confirmUploadUlang('{{ $berkas->jenis }}', {{ $berkas->id }})" title="Upload Ulang">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox mb-2" style="font-size: 1.5rem;"></i><br>
                                        Belum ada berkas yang dikirim
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('pendaftar.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <div>
                    <span id="selected-count" class="text-muted me-3">0 berkas dipilih</span>
                    <button type="button" class="btn btn-primary" id="submit-btn" onclick="confirmFinalisasi()">
                        <i class="fas fa-paper-plane me-1"></i>Upload Berkas
                    </button>
                </div>
            </div>
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

        <!-- Modal Upload Ulang -->
        <div class="modal fade" id="uploadModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Ulang Berkas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="berkasId" name="berkas_id">
                            <div class="mb-3">
                                <label class="form-label">Jenis Berkas: <span id="jenisLabel"></span></label>
                                <input type="file" id="fileInput" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                                <small class="text-muted">Format: PDF, JPG, PNG (Max 2MB)</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.cursor-pointer {
    cursor: pointer;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.badge {
    font-size: 0.75em;
}

#submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.draft-status {
    margin-top: 5px;
}

.position-relative .form-control {
    margin-bottom: 5px;
}

.small-text {
    font-size: 0.875rem;
    line-height: 1.2;
    margin-top: 3px;
    display: block;
}

.required-field {
    border-left: 4px solid #dc3545;
    padding-left: 15px;
    background: #fff5f5;
}

.required-field .form-label {
    font-weight: 600;
    color: #dc3545;
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.auto-save').on('change', function() {
        const input = $(this);
        const file = this.files[0];
        const type = input.data('type');
        const container = input.closest('.position-relative');
        const statusDiv = container.find('.draft-status');
        
        if (!file) return;
        
        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File Terlalu Besar',
                text: 'Ukuran file maksimal 2MB',
                confirmButtonColor: '#28a745'
            });
            input.val('');
            return;
        }
        
        statusDiv.removeClass('d-none');
        
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: '{{ route("pendaftar.berkas.auto-save") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersimpan!',
                    text: 'Berkas berhasil disimpan sebagai draft',
                    confirmButtonColor: '#28a745',
                    timer: 2000,
                    showConfirmButton: false
                });
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                statusDiv.addClass('d-none');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: xhr.responseJSON?.message || 'Gagal menyimpan draft',
                    confirmButtonColor: '#28a745'
                });
                input.val('');
            }
        });
    });
    
    function updateCount() {
        const count = $('input[name="draft_berkas[]"]:checked').length;
        $('#selected-count').text(count + ' berkas dipilih');
    }
    
    $(document).on('change', 'input[name="draft_berkas[]"]', updateCount);
    
    $('#select-all-drafts').on('click', function() {
        const checkboxes = $('input[name="draft_berkas[]"]');
        const allChecked = checkboxes.filter(':checked').length === checkboxes.length;
        checkboxes.prop('checked', !allChecked);
        $(this).text(allChecked ? 'Pilih Semua' : 'Batal Pilih');
        updateCount();
    });
    
    updateCount();
    
    $(document).on('click', '.delete-draft', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Draft?',
            text: 'Yakin ingin menghapus draft berkas ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
            $.ajax({
                url: `/pendaftar/berkas/draft/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    location.reload();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menghapus',
                        text: xhr.responseJSON?.message || 'Gagal menghapus draft',
                        confirmButtonColor: '#28a745'
                    });
                }
            });
            }
        });
    });
});

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

function confirmUploadUlang(jenis, berkasId) {
    Swal.fire({
        title: 'Upload Ulang Berkas?',
        html: `Yakin ingin upload ulang berkas <strong>${jenis.toUpperCase()}</strong>?<br><br>File lama akan diganti dengan file baru.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Upload Ulang!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            showUploadModal(jenis, berkasId);
        }
    });
}

function showUploadModal(jenis, berkasId) {
    document.getElementById('jenisLabel').textContent = jenis.toUpperCase();
    document.getElementById('berkasId').value = berkasId;
    document.getElementById('fileInput').value = '';
    new bootstrap.Modal(document.getElementById('uploadModal')).show();
}

function confirmFinalisasi() {
    const checkedBoxes = document.querySelectorAll('input[name="draft_berkas[]"]:checked');
    if (checkedBoxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Belum Ada Berkas',
            text: 'Pilih minimal 1 berkas draft untuk dikirim!',
            confirmButtonColor: '#28a745'
        });
        return;
    }
    
    // Validasi berkas wajib
    const requiredDocs = ['IJAZAH', 'RAPOR', 'AKTA', 'KK'];
    const selectedDocs = Array.from(checkedBoxes).map(cb => {
        const label = document.querySelector(`label[for="${cb.id}"]`);
        return label.querySelector('strong').textContent;
    });
    
    const missingDocs = requiredDocs.filter(doc => !selectedDocs.includes(doc));
    
    if (missingDocs.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Berkas Wajib Belum Lengkap',
            html: `Berkas wajib yang belum diupload:<br><strong>${missingDocs.join(', ')}</strong><br><br>Silakan upload semua berkas wajib terlebih dahulu.`,
            confirmButtonColor: '#28a745'
        });
        return;
    }
    
    const fileNames = Array.from(checkedBoxes).map(cb => {
        const label = document.querySelector(`label[for="${cb.id}"]`);
        return label.querySelector('strong').textContent;
    });
    
    Swal.fire({
        title: 'Kirim Berkas ke Panitia?',
        html: `Kirim <strong>${checkedBoxes.length} berkas</strong> ke panitia?<br><br><strong>Berkas:</strong> ${fileNames.join(', ')}<br><br><em>Setelah dikirim tidak bisa diubah lagi.</em>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kirim!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('berkas-form').submit();
        }
    });
}

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('fileInput');
    if (!fileInput.files[0]) {
        Swal.fire({
            icon: 'warning',
            title: 'File Belum Dipilih',
            text: 'Pilih file terlebih dahulu!',
            confirmButtonColor: '#28a745'
        });
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const modal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    
    fetch('{{ route("pendaftar.berkas.upload-ulang") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            modal.hide();
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Berkas berhasil diupload ulang!',
                confirmButtonColor: '#28a745',
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => location.reload(), 500);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Upload',
                text: data.message || 'Terjadi kesalahan',
                confirmButtonColor: '#28a745'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error Upload',
            text: error.message || 'Terjadi kesalahan saat upload. Silakan coba lagi.',
            confirmButtonColor: '#28a745'
        });
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Upload';
    });
});
</script>
@endpush