<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <a class="navbar-brand" href="{{ route('admin.master') }}">
                <i class="fas fa-graduation-cap me-2"></i>SPMB - Dashboard Admin
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user-circle me-1"></i>{{ $mockUser->name ?? 'Admin' }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="button" class="btn btn-outline-light btn-sm" onclick="confirmLogout(this)">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4 px-4">
        <div class="row">
            <div class="col-md-2">
                <div class="card sidebar-card">
                    <div class="card-header text-white">
                        <h6 class="mb-0"><i class="fas fa-bars me-2"></i>Menu</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.master') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.master') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.jurusan') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.jurusan') ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap"></i> Jurusan
                        </a>
                        <a href="{{ route('admin.gelombang') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.gelombang') ? 'active' : '' }}">
                            <i class="fas fa-calendar"></i> Gelombang
                        </a>
                        <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Pengguna
                        </a>
                        <a href="{{ route('admin.audit-log') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.audit-log') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> Audit Log
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-10">


                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // SweetAlert untuk session messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3498db'
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#3498db'
            });
        @endif
        
        // Konfirmasi logout
        function confirmLogout(button) {
            Swal.fire({
                title: 'Logout?',
                text: 'Anda yakin ingin keluar dari sistem?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
        
        // Konfirmasi delete
        function confirmDelete(form, itemName = 'item') {
            Swal.fire({
                title: 'Hapus Data?',
                text: `Anda yakin ingin menghapus ${itemName} ini?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
        
        // Konfirmasi submit form
        function confirmSubmit(form, title = 'Simpan Data?', text = 'Anda yakin ingin menyimpan data ini?') {
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tutup modal jika ada
                    const modal = form.closest('.modal');
                    if (modal) {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    }
                    form.submit();
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>