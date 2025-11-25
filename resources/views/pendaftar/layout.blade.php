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
        body { background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: #2c3e50 !important; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .sidebar-card { border: none; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        .sidebar-card .card-header { background: #34495e; border: none; color: white; }
        .list-group-item.active { background: #3498db; border-color: #3498db; }
        .list-group-item:hover:not(.active) { background: #ecf0f1; }
        .content-card { background: white; border: none; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .content-card .card-header { background: white; border-bottom: 2px solid #ecf0f1; font-weight: 600; color: #2c3e50; padding: 15px 20px; }
        .table { color: #2c3e50; }
        .table thead th { background: #ecf0f1; border: none; font-weight: 600; }
        .btn-primary { background: #3498db; border-color: #3498db; }
        .btn-primary:hover { background: #2980b9; border-color: #2980b9; }
        .btn-success { background: #27ae60; border-color: #27ae60; }
        .btn-success:hover { background: #229954; border-color: #229954; }
        .badge { padding: 6px 12px; border-radius: 20px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('pendaftar.dashboard') }}">
                <i class="fas fa-graduation-cap me-2"></i>SPMB - Dashboard Pendaftar
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user-circle me-1"></i>{{ session('user')['name'] ?? 'Pendaftar' }}
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
            <div class="col-md-2">
                @include('pendaftar.sidebar')
            </div>

            <div class="col-md-10">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
