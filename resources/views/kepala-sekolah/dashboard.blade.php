<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Sekolah - SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        }
        
        .sidebar-card { 
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            background: white;
        }
        
        .sidebar-card .card-header { 
            background: #3498db;
            border: none;
            color: white;
            font-weight: 600;
            padding: 18px 20px;
        }
        
        .list-group-item {
            border: none;
            padding: 16px 20px;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .list-group-item.active { 
            background: linear-gradient(90deg, rgba(52, 152, 219, 0.1) 0%, transparent 100%);
            border-color: transparent;
            color: #3498db;
            font-weight: 600;
            border-left: 4px solid #3498db;
        }
        
        .list-group-item:hover:not(.active) { 
            background: #f8f9fa;
            padding-left: 24px;
            color: #3498db;
        }
        
        .stat-box { 
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border-left: 4px solid;
            transition: transform 0.2s;
            margin-bottom: 24px;
        }
        
        .stat-box:hover { 
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .stat-box.blue { border-color: #3498db; }
        .stat-box.green { border-color: #27ae60; }
        .stat-box.orange { border-color: #e67e22; }
        .stat-box.purple { border-color: #9b59b6; }
        
        .stat-icon { 
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .stat-icon.blue { background: #e3f2fd; color: #3498db; }
        .stat-icon.green { background: #e8f5e9; color: #27ae60; }
        .stat-icon.orange { background: #fff3e0; color: #e67e22; }
        .stat-icon.purple { background: #f3e5f5; color: #9b59b6; }
        
        .stat-number { 
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }
        
        .stat-label { 
            color: #7f8c8d;
            font-size: 14px;
            margin: 5px 0 0 0;
        }
        
        .chart-card { 
            background: white;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 24px;
            overflow: hidden;
        }
        
        .chart-card .card-header { 
            background: linear-gradient(to right, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            color: #2c3e50;
            padding: 18px 24px;
        }
        
        .top-list-item { 
            padding: 12px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .top-list-item:last-child { 
            border-bottom: none;
        }
        
        .badge-count { 
            background: #3498db;
            padding: 6px 12px;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>SPMB - Dashboard Kepala Sekolah
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user-circle me-1"></i>{{ $mockUser->name }}
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

    <div class="container-fluid mt-4 px-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2">
                <div class="card sidebar-card">
                    <div class="card-header text-white">
                        <h6 class="mb-0"><i class="fas fa-bars me-2"></i>Menu</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('kepala-sekolah.dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="{{ route('kepala-sekolah.pendaftar') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-users"></i> Daftar Calon Siswa
                        </a>
                        <a href="{{ route('kepala-sekolah.diterima') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-check-circle"></i> Siswa Diterima
                        </a>
                        <a href="{{ route('kepala-sekolah.pembayaran') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-money-bill"></i> Rekap Pembayaran
                        </a>
                        <a href="{{ route('kepala-sekolah.asal-sekolah') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-school"></i> Data Asal Sekolah
                        </a>
                        <a href="{{ route('kepala-sekolah.asal-wilayah') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-map-marker-alt"></i> Asal Wilayah
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="stat-box blue">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="stat-number">{{ $totalPendaftar }}</p>
                                    <p class="stat-label">Total Pendaftar</p>
                                </div>
                                <div class="stat-icon blue">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-box green">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="stat-number">{{ $pendaftarDiterima }}</p>
                                    <p class="stat-label">Siswa Diterima</p>
                                </div>
                                <div class="stat-icon green">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-box orange">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @php
                                        // Query langsung untuk memastikan data terbaru
                                        $realTotal = \App\Models\PendaftarPembayaran::where('status', 'VERIFIED')->sum('nominal');
                                        $nominal = $realTotal > 0 ? $realTotal : 0;
                                        $formatted = $nominal >= 1000000 
                                            ? 'Rp ' . number_format($nominal / 1000000, 1) . 'jt'
                                            : ($nominal > 0 ? 'Rp ' . number_format($nominal / 1000, 0) . 'rb' : 'Rp 0');
                                    @endphp
                                    <p class="stat-number" style="font-size: 20px;">{{ $formatted }}</p>
                                    <p class="stat-label">Total Pembayaran</p>
                                </div>
                                <div class="stat-icon orange">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stat-box purple">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="stat-number">{{ $asalSekolah->count() }}</p>
                                    <p class="stat-label">Asal Sekolah</p>
                                </div>
                                <div class="stat-icon purple">
                                    <i class="fas fa-school"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div class="chart-card">
                            <div class="card-header">
                                <i class="fas fa-chart-line me-2"></i>Trend Pendaftar (6 Bulan Terakhir)
                            </div>
                            <div class="card-body">
                                <canvas id="chartBulanan" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="chart-card">
                            <div class="card-header">
                                <i class="fas fa-chart-pie me-2"></i>Distribusi Jurusan
                            </div>
                            <div class="card-body">
                                <canvas id="chartJurusan" height="180"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Charts Row -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="chart-card">
                            <div class="card-header">
                                <i class="fas fa-chart-bar me-2"></i>Status Pendaftaran
                            </div>
                            <div class="card-body">
                                <canvas id="chartStatus" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Schools & Regions -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="chart-card">
                            <div class="card-header">
                                <i class="fas fa-school me-2"></i>Top 5 Asal Sekolah
                            </div>
                            <div class="card-body">
                                @foreach($asalSekolah->take(5) as $sekolah)
                                <div class="top-list-item d-flex justify-content-between align-items-center">
                                    <span style="color: #2c3e50;">{{ $sekolah->nama_sekolah }}</span>
                                    <span class="badge-count text-white">{{ $sekolah->total }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="chart-card">
                            <div class="card-header">
                                <i class="fas fa-map-marker-alt me-2"></i>Top 5 Asal Wilayah
                            </div>
                            <div class="card-body">
                                @foreach($asalWilayah->take(5) as $wilayah)
                                <div class="top-list-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div style="color: #2c3e50; font-weight: 500;">{{ $wilayah->regency->name ?? 'Tidak Diketahui' }}</div>
                                        <small style="color: #95a5a6;">{{ $wilayah->regency->province->name ?? '' }}</small>
                                    </div>
                                    <span class="badge-count text-white">{{ $wilayah->total }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Chart Trend Bulanan
        new Chart(document.getElementById('chartBulanan'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($dataBulanan, 'bulan')) !!},
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: {!! json_encode(array_column($dataBulanan, 'total')) !!},
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#3498db',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: { backgroundColor: '#2c3e50', padding: 12, titleFont: { size: 14 }, bodyFont: { size: 13 } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#ecf0f1' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Chart Distribusi Jurusan
        new Chart(document.getElementById('chartJurusan'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($dataJurusan->pluck('nama')) !!},
                datasets: [{
                    data: {!! json_encode($dataJurusan->pluck('pendaftar_count')) !!},
                    backgroundColor: ['#3498db', '#27ae60', '#e67e22', '#e74c3c', '#9b59b6', '#f39c12'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 15, font: { size: 12 } } },
                    tooltip: { backgroundColor: '#2c3e50', padding: 12 }
                }
            }
        });

        // Chart Status Pendaftaran
        const statusData = [
            {label: 'Dikirim', count: {{ \App\Models\Pendaftar::where('status', 'SUBMIT')->count() }}},
            {label: 'Lulus Adm', count: {{ \App\Models\Pendaftar::where('status', 'ADM_PASS')->count() }}},
            {label: 'Pending Bayar', count: {{ \App\Models\Pendaftar::where('status', 'PAYMENT_PENDING')->count() }}},
            {label: 'Terbayar', count: {{ \App\Models\Pendaftar::where('status', 'PAID')->count() }}},
            {label: 'Diterima', count: {{ \App\Models\Pendaftar::where('status', 'ACCEPTED')->count() }}},
            {label: 'Ditolak', count: {{ \App\Models\Pendaftar::whereIn('status', ['ADM_REJECT', 'REJECTED'])->count() }}}
        ];

        new Chart(document.getElementById('chartStatus'), {
            type: 'bar',
            data: {
                labels: statusData.map(d => d.label),
                datasets: [{
                    label: 'Jumlah',
                    data: statusData.map(d => d.count),
                    backgroundColor: ['#f39c12', '#3498db', '#e67e22', '#16a085', '#27ae60', '#e74c3c'],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: { backgroundColor: '#2c3e50', padding: 12 }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#ecf0f1' } },
                    x: { grid: { display: false } }
                }
            }
        });


    </script>
</body>
</html>