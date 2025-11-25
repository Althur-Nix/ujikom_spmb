@extends('layouts.admin')

@section('title', 'Audit Log')

@section('content')
<div class="content-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-history me-2"></i>Audit Log Sistem
        </div>
        <form method="GET" class="d-flex gap-2">
            <select name="user_id" class="form-select form-select-sm" style="width: 200px;">
                <option value="">Semua User</option>
                @if(isset($users))
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" style="width: 150px;">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('admin.audit-log') }}" class="btn btn-sm btn-secondary">Reset</a>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="150">Waktu</th>
                        <th width="150">User</th>
                        <th>Aksi</th>
                        <th>Objek</th>
                        <th width="100">ID Objek</th>
                        <th width="80">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs ?? [] as $log)
                    <tr>
                        <td><small>{{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}</small></td>
                        <td>
                            <strong>{{ $log->user ? $log->user->name : 'System' }}</strong><br>
                            <small class="text-muted">{{ $log->user ? $log->user->role : '-' }}</small>
                        </td>
                        <td>
                            @php
                                $aksi = strtolower($log->aksi ?? '');
                                if (str_contains($aksi, 'login')) {
                                    $badgeClass = 'bg-success';
                                } elseif (str_contains($aksi, 'logout')) {
                                    $badgeClass = 'bg-secondary';
                                } elseif (str_contains($aksi, 'create') || str_contains($aksi, 'tambah')) {
                                    $badgeClass = 'bg-primary';
                                } elseif (str_contains($aksi, 'update') || str_contains($aksi, 'edit')) {
                                    $badgeClass = 'bg-warning';
                                } elseif (str_contains($aksi, 'delete') || str_contains($aksi, 'hapus')) {
                                    $badgeClass = 'bg-danger';
                                } elseif (str_contains($aksi, 'verifikasi')) {
                                    $badgeClass = 'bg-info';
                                } else {
                                    $badgeClass = 'bg-secondary';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $log->aksi ?? '-' }}</span>
                        </td>
                        <td>{{ $log->objek ?? '-' }}</td>
                        <td class="text-center">{{ $log->objek_id ?? '-' }}</td>
                        <td class="text-center">
                            @if($log->meta)
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#metaModal{{ $log->id }}">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                                
                                <div class="modal fade" id="metaModal{{ $log->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Aktivitas</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <pre>{{ json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada log aktivitas</h5>
                            <p class="text-muted">Log aktivitas sistem akan muncul di sini setelah ada aktivitas user</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        

    </div>
</div>
@endsection
