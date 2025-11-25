<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pendaftar - SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body { 
            background: #f5f7fa;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
        }
        
        .navbar { 
            background: #2c3e50 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
            padding: 12px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            color: white !important;
        }
        
        .navbar-brand i {
            margin-right: 10px;
        }
        
        .navbar-text {
            color: white !important;
        }
        
        .btn-outline-light {
            border-radius: 10px;
            padding: 8px 20px;
            font-weight: 500;
            border: 2px solid rgba(255,255,255,0.3);
            transition: all 0.3s ease;
        }
        
        .btn-outline-light:hover {
            background: white;
            color: #2c3e50;
            border-color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,255,255,0.3);
        }
        
        .sidebar-card { 
            border: none;
            border-radius: 0;
            box-shadow: none;
            overflow: visible;
            background: white;
            height: 100%;
        }
        
        .sidebar-card .card-header { 
            background: #3498db;
            border: none;
            color: white;
            padding: 18px 20px;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.3px;
        }
        
        .list-group-item {
            border: none;
            border-radius: 0;
            padding: 16px 20px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 14px;
            color: #2c3e50;
            position: relative;
            overflow: hidden;
        }
        
        .list-group-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #3498db;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .list-group-item.active::before {
            transform: scaleY(1);
        }
        
        .list-group-item.active { 
            background: linear-gradient(90deg, rgba(52, 152, 219, 0.1) 0%, transparent 100%);
            border-color: transparent;
            color: #3498db;
            font-weight: 600;
        }
        
        .list-group-item:hover:not(.active) { 
            background: #f8f9fa;
            padding-left: 24px;
            color: #3498db;
        }
        
        .list-group-item i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        
        .content-card { 
            background: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 24px;
            overflow: hidden;
        }
        
        .content-card .card-header { 
            background: linear-gradient(to right, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            color: #2c3e50;
            padding: 18px 24px;
            font-size: 16px;
        }
        
        .content-card .card-body {
            padding: 24px;
        }
        
        .stat-box { 
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-left: 5px solid;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stat-box:hover { 
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .stat-box.blue { 
            border-color: #3498db;
            background: white;
        }
        
        .stat-box.green { 
            border-color: #27ae60;
            background: white;
        }
        
        .sidebar-wrapper {
            position: fixed;
            left: 0;
            top: 60px;
            height: calc(100vh - 60px);
            width: 250px;
            transition: all 0.3s ease;
            z-index: 1000;
            background: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        
        .sidebar-wrapper.collapsed {
            left: -250px;
        }
        
        .content-wrapper {
            margin-left: 250px;
            margin-top: 60px;
            transition: all 0.3s ease;
            padding: 24px;
            min-height: calc(100vh - 60px);
        }
        
        .content-wrapper.expanded {
            margin-left: 0;
        }
        
        .sidebar-toggle {
            position: fixed;
            left: 250px;
            top: 65px;
            z-index: 1001;
            background: white;
            border: 2px solid #3498db;
            border-left: none;
            border-radius: 0 50px 50px 0;
            padding: 0;
            box-shadow: 3px 3px 15px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            color: #3498db;
            font-size: 20px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-toggle:hover {
            background: #3498db;
            color: white;
            transform: translateX(5px) scale(1.05);
            box-shadow: 4px 4px 20px rgba(52, 152, 219, 0.3);
        }
        
        .sidebar-toggle:active {
            transform: translateX(5px) scale(0.98);
        }
        
        .sidebar-toggle.collapsed {
            left: 0;
            border-radius: 0 50px 50px 0;
        }
        
        .sidebar-toggle.collapsed:hover {
            transform: translateX(5px) scale(1.05);
        }
        
        .table { 
            color: #2c3e50;
        }
        
        .table-borderless td {
            padding: 10px 8px;
        }
        
        .badge { 
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .badge.fs-6 {
            font-size: 14px !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('pendaftar.dashboard') }}">
                <i class="fas fa-graduation-cap"></i>
                <span>SPMB - Dashboard Pendaftar</span>
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user-circle me-1"></i>{{ session('user.name') ?? 'User' }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <button class="sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="sidebar-wrapper" id="sidebarWrapper">
        @include('pendaftar.sidebar')
    </div>
    
    <div class="content-wrapper" id="contentWrapper">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="stat-box blue">
                            <div class="card-body">
                                <h6 class="mb-3" style="color: #7f8c8d;">Status Pendaftaran</h6>
                                <div>
                                    @switch($pendaftar->status)
                                        @case('SUBMIT')
                                            <span class="badge bg-warning fs-6">Berhasil mengisi form - Silakan Upload Berkas</span>
                                            @break
                                        @case('REVISION_REQUIRED')
                                            <span class="badge bg-warning fs-6">Berkas Perlu Revisi - Upload Ulang Berkas</span>
                                            @break
                                        @case('ADM_PASS')
                                            <span class="badge bg-info fs-6">Lulus Verifikasi Berkas - Silakan Bayar</span>
                                            @break
                                        @case('ADM_REJECT')
                                            <span class="badge bg-danger fs-6">Ditolak Administrasi</span>
                                            @break
                                        @case('PAYMENT_PENDING')
                                            <span class="badge bg-warning fs-6">Menunggu Verifikasi Pembayaran</span>
                                            @break
                                        @case('PAID')
                                            <span class="badge bg-success fs-6">Sudah Bayar - Menunggu Seleksi</span>
                                            @break
                                        @case('ACCEPTED')
                                            <span class="badge bg-primary fs-6">DITERIMA - Selamat!</span>
                                            @break
                                        @case('REJECTED')
                                            <span class="badge bg-danger fs-6">DITOLAK</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary fs-6">{{ $pendaftar->status }}</span>
                                    @endswitch
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-box green">
                            <div class="card-body">
                                <h6 class="mb-3" style="color: #7f8c8d;">No. Pendaftaran</h6>
                                <div>
                                    <h4 style="color: #2c3e50; font-weight: 700;">{{ $pendaftar->no_pendaftaran ?? 'Belum ada' }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-card">
                    <div class="card-header">
                        <i class="fas fa-user me-2"></i>Informasi Pendaftar
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nama Lengkap</strong></td>
                                        <td>: {{ $pendaftar->dataSiswa->nama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NISN</strong></td>
                                        <td>: {{ $pendaftar->dataSiswa->nisn ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jurusan Pilihan</strong></td>
                                        <td>: {{ $pendaftar->jurusan->nama ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Jenis Kelamin</strong></td>
                                        <td>: {{ $pendaftar->dataSiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tempat, Tanggal Lahir</strong></td>
                                        <td>: {{ $pendaftar->dataSiswa->tempat_lahir ?? '-' }}, {{ $pendaftar->dataSiswa->tgl_lahir ? $pendaftar->dataSiswa->tgl_lahir->format('d M Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Asal Sekolah</strong></td>
                                        <td>: {{ $pendaftar->asalSekolah->nama_sekolah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Wilayah</strong></td>
                                        <td>: {{ $pendaftar->dataSiswa->village->name ?? '' }}{{ $pendaftar->dataSiswa->village ? ', ' : '' }}{{ $pendaftar->dataSiswa->district->name ?? '' }}{{ $pendaftar->dataSiswa->district ? ', ' : '' }}{{ $pendaftar->dataSiswa->regency->name ?? '' }}{{ $pendaftar->dataSiswa->regency ? ', ' : '' }}{{ $pendaftar->dataSiswa->province->name ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($pendaftar->dataOrtu)
                <div class="content-card">
                    <div class="card-header">
                        <i class="fas fa-users me-2"></i>Data Orang Tua/Wali
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nama Ayah</strong></td>
                                        <td>: {{ $pendaftar->dataOrtu->nama_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pekerjaan Ayah</strong></td>
                                        <td>: {{ $pendaftar->dataOrtu->pekerjaan_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP Ayah</strong></td>
                                        <td>: {{ $pendaftar->dataOrtu->hp_ayah ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nama Ibu</strong></td>
                                        <td>: {{ $pendaftar->dataOrtu->nama_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pekerjaan Ibu</strong></td>
                                        <td>: {{ $pendaftar->dataOrtu->pekerjaan_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>No. HP Ibu</strong></td>
                                        <td>: {{ $pendaftar->dataOrtu->hp_ibu ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if($pendaftar->dataOrtu->wali_nama || $pendaftar->dataOrtu->wali_hp)
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nama Wali</strong></td>
                                        <td>: {{ $pendaftar->dataOrtu->wali_nama ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>No. HP Wali</strong></td>
                                        <td>: {{ $pendaftar->dataOrtu->wali_hp ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($pendaftar->asalSekolah)
                <div class="content-card">
                    <div class="card-header">
                        <i class="fas fa-school me-2"></i>Data Asal Sekolah
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nama Sekolah</strong></td>
                                        <td>: {{ $pendaftar->asalSekolah->nama_sekolah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>NPSN</strong></td>
                                        <td>: {{ $pendaftar->asalSekolah->npsn ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Kabupaten</strong></td>
                                        <td>: {{ $pendaftar->asalSekolah->kabupaten ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nilai Rata-rata</strong></td>
                                        <td>: {{ $pendaftar->asalSekolah->nilai_rata ? number_format($pendaftar->asalSekolah->nilai_rata, 2) : '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarWrapper');
            const content = document.getElementById('contentWrapper');
            const toggle = document.getElementById('sidebarToggle');
            
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
            toggle.classList.toggle('collapsed');
            
            const icon = toggle.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-bars');
            }
        }
    </script>
</body>
</html>