@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="content-card mb-3">
            <div class="card-header">
                <i class="fas fa-user-plus me-2"></i>Tambah Pengguna
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Nama Lengkap">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="email@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Minimal 6 karakter">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror">
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="panitia" {{ old('role') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                            <option value="keuangan" {{ old('role') == 'keuangan' ? 'selected' : '' }}>Keuangan</option>
                            <option value="kepala_sekolah" {{ old('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Catatan: Role "Pendaftar" hanya bisa dibuat melalui registrasi publik dengan verifikasi OTP</small>
                    </div>
                    
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(this.closest('form'), 'Tambah Pengguna?', 'Data pengguna akan disimpan ke sistem')">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="content-card">
            <div class="card-header">
                <i class="fas fa-users me-2"></i>Daftar Pengguna
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @switch($user->role)
                                            @case('admin')
                                                <span class="badge bg-primary">Admin</span>
                                                @break
                                            @case('verifikator_adm')
                                                <span class="badge bg-warning">Verifikator</span>
                                                @break
                                            @case('keuangan')
                                                <span class="badge bg-success">Keuangan</span>
                                                @break
                                            @case('kepsek')
                                                <span class="badge bg-info">Kepala Sekolah</span>
                                                @break
                                            @case('pendaftar')
                                                <span class="badge bg-dark">Pendaftar</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ $user->role }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(this.closest('form'), 'user {{ $user->name }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data pengguna</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                        <input type="password" name="password" id="editPassword" class="form-control">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="editRole" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="panitia">Panitia</option>
                            <option value="keuangan">Keuangan</option>
                            <option value="kepala_sekolah">Kepala Sekolah</option>
                        </select>
                        <small class="text-muted">Role "Pendaftar" tidak dapat diubah dari sini</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="confirmSubmit(this.closest('form'), 'Update Pengguna?', 'Perubahan data akan disimpan')">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function editUser(id, name, email, role) {
    document.getElementById('editForm').action = `/admin/users/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value = role;
    document.getElementById('editPassword').value = '';
    
    // Disable role dropdown jika user adalah pendaftar
    const roleSelect = document.getElementById('editRole');
    if (role === 'pendaftar') {
        roleSelect.disabled = true;
        // Tambahkan option pendaftar jika belum ada
        if (!roleSelect.querySelector('option[value="pendaftar"]')) {
            const option = document.createElement('option');
            option.value = 'pendaftar';
            option.textContent = 'Pendaftar (Tidak dapat diubah)';
            roleSelect.appendChild(option);
        }
        roleSelect.value = 'pendaftar';
    } else {
        roleSelect.disabled = false;
        // Hapus option pendaftar jika ada
        const pendaftarOption = roleSelect.querySelector('option[value="pendaftar"]');
        if (pendaftarOption) {
            pendaftarOption.remove();
        }
    }
    
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endpush