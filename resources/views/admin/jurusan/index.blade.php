@extends('layouts.admin')

@section('content')
<div class="content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-graduation-cap me-2"></i>Kelola Jurusan
        </div>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJurusanModal">
            <i class="fas fa-plus me-1"></i>Tambah Jurusan
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Jurusan</th>
                        <th>Kuota</th>
                        <th>Pendaftar</th>
                        <th>Sisa Kuota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jurusan as $j)
                    <tr>
                        <td><strong>{{ $j->kode }}</strong></td>
                        <td>{{ $j->nama }}</td>
                        <td>{{ $j->kuota ?? 0 }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $j->pendaftar_count ?? 0 }}</span>
                        </td>
                        <td>
                            @php
                                $sisa = ($j->kuota ?? 0) - ($j->pendaftar_count ?? 0);
                            @endphp
                            <span class="badge {{ $sisa > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $sisa }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary me-1" 
                                    onclick="editJurusan({{ $j->id }}, '{{ $j->kode }}', '{{ $j->nama }}', {{ $j->kuota ?? 0 }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('jurusan.destroy', $j->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="confirmDelete(this.closest('form'), 'jurusan {{ $j->nama }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data jurusan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Jurusan Modal -->
<div class="modal fade" id="addJurusanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('jurusan.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Jurusan</label>
                        <input type="text" name="kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kuota</label>
                        <input type="number" name="kuota" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(this.closest('form'), 'Tambah Jurusan?', 'Data jurusan akan disimpan ke sistem')">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Jurusan Modal -->
<div class="modal fade" id="editJurusanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editJurusanForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Jurusan</label>
                        <input type="text" name="kode" id="edit_kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Jurusan</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kuota</label>
                        <input type="number" name="kuota" id="edit_kuota" class="form-control" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(this.closest('form'), 'Update Jurusan?', 'Perubahan data akan disimpan')">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editJurusan(id, kode, nama, kuota) {
    document.getElementById('edit_kode').value = kode;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_kuota').value = kuota;
    document.getElementById('editJurusanForm').action = '/admin/jurusan/' + id;
    
    var modal = new bootstrap.Modal(document.getElementById('editJurusanModal'));
    modal.show();
}
</script>
@endpush