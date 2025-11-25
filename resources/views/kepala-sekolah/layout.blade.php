<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
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
        }
        
        .table { 
            color: #2c3e50;
        }
        
        .table thead th { 
            background: #ecf0f1;
            border: none;
            font-weight: 600;
        }
        
        .btn-primary { 
            background: #3498db;
            border-color: #3498db;
        }
        
        .btn-primary:hover { 
            background: #2980b9;
            border-color: #2980b9;
        }
        
        .btn-success { 
            background: #27ae60;
            border-color: #27ae60;
        }
        
        .btn-success:hover { 
            background: #229954;
            border-color: #229954;
        }
        
        .badge { 
            padding: 8px 16px;
            border-radius: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('kepala-sekolah.dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>SPMB - Dashboard Kepala Sekolah
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user-circle me-1"></i>{{ session('user')['name'] ?? 'Kepala Sekolah' }}
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
                        <a href="{{ route('kepala-sekolah.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('kepala-sekolah.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="{{ route('kepala-sekolah.pendaftar') }}" class="list-group-item list-group-item-action {{ request()->routeIs('kepala-sekolah.pendaftar') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Daftar Calon Siswa
                        </a>
                        <a href="{{ route('kepala-sekolah.diterima') }}" class="list-group-item list-group-item-action {{ request()->routeIs('kepala-sekolah.diterima') ? 'active' : '' }}">
                            <i class="fas fa-check-circle"></i> Siswa Diterima
                        </a>
                        <a href="{{ route('kepala-sekolah.pembayaran') }}" class="list-group-item list-group-item-action {{ request()->routeIs('kepala-sekolah.pembayaran') ? 'active' : '' }}">
                            <i class="fas fa-money-bill"></i> Rekap Pembayaran
                        </a>
                        <a href="{{ route('kepala-sekolah.asal-sekolah') }}" class="list-group-item list-group-item-action {{ request()->routeIs('kepala-sekolah.asal-sekolah') ? 'active' : '' }}">
                            <i class="fas fa-school"></i> Data Asal Sekolah
                        </a>
                        <a href="{{ route('kepala-sekolah.asal-wilayah') }}" class="list-group-item list-group-item-action {{ request()->routeIs('kepala-sekolah.asal-wilayah') ? 'active' : '' }}">
                            <i class="fas fa-map-marker-alt"></i> Asal Wilayah
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
