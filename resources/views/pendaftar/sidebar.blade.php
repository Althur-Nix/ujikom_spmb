<div class="card sidebar-card">
    <div class="card-header text-white">
        <h6 class="mb-0"><i class="fas fa-th-large me-2"></i>Menu</h6>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('pendaftar.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('pendaftar.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('pendaftar.form') }}" class="list-group-item list-group-item-action {{ request()->routeIs('pendaftar.form') ? 'active' : '' }}">
            <i class="fas fa-edit"></i> Form Pendaftaran
        </a>
        <a href="{{ route('pendaftar.berkas') }}" class="list-group-item list-group-item-action {{ request()->routeIs('pendaftar.berkas') ? 'active' : '' }}">
            <i class="fas fa-upload"></i> Upload Berkas
        </a>
        <a href="{{ route('pendaftar.status') }}" class="list-group-item list-group-item-action {{ request()->routeIs('pendaftar.status') ? 'active' : '' }}">
            <i class="fas fa-info-circle"></i> Status Pendaftaran
        </a>
        <a href="{{ route('pendaftar.pembayaran') }}" class="list-group-item list-group-item-action {{ request()->routeIs('pendaftar.pembayaran') ? 'active' : '' }}">
            <i class="fas fa-credit-card"></i> Pembayaran
        </a>
        <a href="{{ route('pendaftar.cetak') }}" class="list-group-item list-group-item-action {{ request()->routeIs('pendaftar.cetak') ? 'active' : '' }}">
            <i class="fas fa-print"></i> Cetak Kartu
        </a>
    </div>
</div>