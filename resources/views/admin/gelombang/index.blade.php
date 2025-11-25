@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-white mb-0">
                    <i class="fas fa-calendar me-2"></i>Kelola Gelombangs
                </h2>
                <p class="text-white-50">Manajemen periode pendaftaran</p>
            </div>
            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addGelombangModal">
                <i class="fas fa-plus me-2"></i>Tambah Gelombang
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Daftar Gelombang
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama Gelombang</th>
                        <th>Tahun</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Biaya Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gelombang as $g)
                    <tr>
                        <td><strong>{{ $g->nama }}</strong></td>
                        <td>{{ $g->tahun }}</td>
                        <td>{{ $g->tgl_mulai ? \Carbon\Carbon::parse($g->tgl_mulai)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $g->tgl_selesai ? \Carbon\Carbon::parse($g->tgl_selesai)->format('d/m/Y') : '-' }}</td>
                        <td>Rp {{ number_format($g->biaya_daftar ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $now = now();
                                $mulai = $g->tgl_mulai ? \Carbon\Carbon::parse($g->tgl_mulai) : null;
                                $selesai = $g->tgl_selesai ? \Carbon\Carbon::parse($g->tgl_selesai) : null;
                            @endphp
                            
                            @if($mulai && $selesai)
                                @if($now < $mulai)
                                    <span class="badge bg-secondary">Belum Dimulai</span>
                                @elseif($now >= $mulai && $now <= $selesai)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Selesai</span>
                                @endif
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" 
                                    onclick="editGelombang({{ $g->id }}, '{{ $g->nama }}', {{ $g->tahun }}, '{{ $g->tgl_mulai }}', '{{ $g->tgl_selesai }}', {{ $g->biaya_daftar ?? 0 }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('gelombang.destroy', $g->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete(this.closest('form'), 'gelombang {{ $g->nama }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                            Belum ada data gelombang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Gelombang Modal -->
<div class="modal fade" id="addGelombangModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Gelombang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('gelombang.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Gelombang</label>
                        <input type="text" name="nama" class="form-control" placeholder="Gelombang 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biaya Pendaftaran</label>
                        <input type="number" name="biaya_daftar" class="form-control" placeholder="150000" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(this.closest('form'), 'Tambah Gelombang?', 'Data gelombang akan disimpan ke sistem')">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Gelombang Modal -->
<div class="modal fade" id="editGelombangModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Gelombang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editGelombangForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Gelombang</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="tahun" id="edit_tahun" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" id="edit_tgl_mulai" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" id="edit_tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biaya Pendaftaran</label>
                        <input type="number" name="biaya_daftar" id="edit_biaya_daftar" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(this.closest('form'), 'Update Gelombang?', 'Perubahan data akan disimpan')">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editGelombang(id, nama, tahun, tgl_mulai, tgl_selesai, biaya_daftar) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_tahun').value = tahun;
    document.getElementById('edit_tgl_mulai').value = tgl_mulai;
    document.getElementById('edit_tgl_selesai').value = tgl_selesai;
    document.getElementById('edit_biaya_daftar').value = biaya_daftar;
    document.getElementById('editGelombangForm').action = '/admin/gelombang/' + id;
    
    var modal = new bootstrap.Modal(document.getElementById('editGelombangModal'));
    modal.show();
}
</script>
@endpush