<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SPMB SMK BAKTI NUSANTARA 666</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        .navbar {
            background: rgba(255,255,255,0.1) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
            padding: 15px 0;
        }
        
        .navbar.scrolled {
            background: rgba(255,255,255,0.95) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 800 !important;
            font-size: 1.5rem !important;
            color: white !important;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled .navbar-brand {
            color: #333 !important;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            margin: 0 10px;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled .nav-link {
            color: #333 !important;
        }
        
        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }
        
        .navbar.scrolled .nav-link:hover {
            color: #2da0a8 !important;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: #2da0a8;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .hero-section {
            padding: 120px 0;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section h1 {
            font-weight: 800;
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease;
        }
        
        .hero-section .lead {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.2s both;
        }
        
        .hero-section .d-flex {
            animation: fadeInUp 1s ease 0.4s both;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #2da0a8, #4ecdc4);
            border: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(45, 160, 168, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, #238a91, #3bb5b8);
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(45, 160, 168, 0.4);
        }
        
        .btn-outline-light {
            border: 2px solid rgba(255,255,255,0.8);
            color: white;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            background: transparent;
        }
        
        .btn-outline-light:hover {
            background: white;
            color: #333;
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255,255,255,0.2);
        }
        
        .card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }
        
        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .card i {
            transition: all 0.3s ease;
        }
        
        .card:hover i {
            transform: scale(1.2) rotate(5deg);
            color: #2da0a8;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        
        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        #authModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 9999;
        }
        
        #container {
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.35);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }
        
        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
            width: 50%;
        }
        
        .sign-in {
            left: 0;
            z-index: 2;
        }
        
        .sign-up {
            left: 0;
            opacity: 0;
            z-index: 1;
        }
        
        #container.active .sign-in {
            transform: translateX(100%);
        }
        
        #container.active .sign-up {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
        }
        
        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }
        
        #container.active .toggle-container {
            transform: translateX(-100%);
            border-radius: 0 150px 100px 0;
        }
        
        .toggle {
            background: linear-gradient(to right, #5c6bc0, #2da0a8);
            height: 100%;
            color: #fff;
            position: relative;
            left: -100%;
            width: 200%;
            transform: translateX(0);
            transition: all 0.6s ease-in-out;
        }
        
        #container.active .toggle {
            transform: translateX(50%);
        }
        
        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transition: all 0.6s ease-in-out;
        }
        
        .toggle-left {
            transform: translateX(-200%);
        }
        
        #container.active .toggle-left {
            transform: translateX(0);
        }
        
        .toggle-right {
            right: 0;
            transform: translateX(0);
        }
        
        #container.active .toggle-right {
            transform: translateX(200%);
        }
        
        /* Disable Edge password reveal button */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                SPMB <span class="text-primary">SMK BAKTI NUSANTARA 666</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profil">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jurusan">Jurusan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#prestasi">Prestasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fasilitas">Fasilitas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pendaftaran">Pendaftaran</a>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-primary ms-2" onclick="document.getElementById('authModal').style.display='block'">Daftar Sekarang!</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Selamat Datang di SMK BAKTI NUSANTARA 666</h1>
                    <p class="lead mb-4">Daftar online dengan mudah dan pantau status pendaftaran secara real-time.</p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                        <button class="btn btn-primary btn-lg" onclick="document.getElementById('authModal').style.display='block'">Daftar Sekarang</button>
                        <a href="#jurusan" class="btn btn-outline-light btn-lg">Lihat Jurusan</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{asset('assets/images/poste11r.jpg')}}" alt="SMK Poster" class="img-fluid rounded shadow floating" style="max-height: 500px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Profil Sekolah -->
    <section id="profil" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Profil SMK Bakti Nusantara 666</h2>
                    <p class="lead text-muted">Sekolah Menengah Kejuruan Unggulan dengan Akreditasi A</p>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4">
                    <h3 class="h4 mb-3">Tentang Sekolah</h3>
                    <p>SMK Bakti Nusantara 666 adalah sekolah menengah kejuruan yang berkomitmen menghasilkan lulusan kompeten, berkarakter, dan siap kerja dengan fasilitas modern dan tenaga pengajar berpengalaman.</p>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-primary">A</h4>
                                <small>Akreditasi</small>
                            </div>  
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="text-primary">25+</h4>
                                <small>Tahun Berpengalaman</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{asset ('assets/images/gedungsekolah.jpg')}}" class="img-fluid rounded shadow" alt="Gedung Sekolah">
                </div>
            </div>
        </div>
    </section>

    <!-- Jurusan -->
    <section id="jurusan" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Program Keahlian</h2>
                    <p class="lead text-muted">Pilih jurusan sesuai minat dan bakatmu</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{asset('assets/images/jurusan/logo-dkv.png')}}" alt="Logo DKV" class="mb-3" style="width: 80px; height: 80px;">
                            <h5>Desain Komunikasi Visual (DKV)</h5>
                            <p class="text-muted">Mempelajari desain grafis, branding, ilustrasi, dan komunikasi visual</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{asset('assets/images/jurusan/logo-pplg.png')}}" alt="Logo PPLG" class="mb-3" style="width: 80px; height: 80px;">
                            <h5>Pengembangan Perangkat Lunak & Gim (PPLG)</h5>
                            <p class="text-muted">Mengembangkan aplikasi, website, dan game dengan teknologi terkini</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{asset('assets/images/jurusan/logo-anm.png')}}" alt="Logo ANM" class="mb-3" style="width: 80px; height: 80px;">
                            <h5>Animasi (ANM)</h5>
                            <p class="text-muted">Mempelajari animasi 2D/3D, motion graphics, dan produksi multimedia</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{asset('assets/images/jurusan/logo-akt.png')}}" alt="Logo AKT" class="mb-3" style="width: 80px; height: 80px;">
                            <h5>Akuntansi (AKT)</h5>
                            <p class="text-muted">Mempelajari pembukuan, laporan keuangan, dan sistem informasi akuntansi</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <img src="{{asset('assets/images/jurusan/logo-BDP.png')}}" alt="Logo PEM" class="mb-3" style="width: 80px; height: 80px;">
                            <h5>Pemasaran (PEM)</h5>
                            <p class="text-muted">Mempelajari strategi pemasaran, digital marketing, dan manajemen penjualan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Prestasi -->
    <section id="prestasi" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Prestasi Sekolah</h2>
                    <p class="lead text-muted">Berbagai pencapaian membanggakan siswa-siswi kami</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-trophy fa-2x text-warning mb-3"></i>
                            <h5>Juara 2 LOMBA PUISI</h5>
                            <p class="text-muted">LOMBA PUISI 2025</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-medal fa-2x text-success mb-3"></i>
                            <h5>Juara 2 NASIONAL BKC</h5>
                            <p class="text-muted">JUARA KARATE 2025</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-award fa-2x text-info mb-3"></i>
                            <h5>Juara 1 NASIONAL UNIBI BANDUNG</h5>
                            <p class="text-muted">Web Programming Contest 2025</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-star fa-2x text-primary mb-3"></i>
                            <h5>Sekolah Terbaik</h5>
                            <p class="text-muted">Tingkat Kab Bandung</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fasilitas -->
    <section id="fasilitas" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Fasilitas Sekolah</h2>
                    <p class="lead text-muted">Fasilitas lengkap untuk mendukung pembelajaran optimal</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="{{asset('assets/images/labpk1.jpg')}}" alt="Lab Komputer}}" class="card-img-top" alt="Lab Komputer">
                        <div class="card-body">
                            <h5>Laboratorium Komputer</h5>
                            <p class="text-muted">3 Lab komputer dengan 120 unit PC terbaru dan koneksi internet 5G</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="{{asset('assets/images/labpk2.jpg')}}" alt="Lab Jaringan}}" class="card-img-top" alt="Lab Jaringan">
                        <div class="card-body">
                            <h5>Laboratorium Jaringan</h5>
                            <p class="text-muted">Lab jaringan lengkap dengan peralatan dan server untuk praktik</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="{{('assets/images/lapangan1.jpg')}}" alt="lapangan}}" class="card-img-top" alt="Studio Multimedia">
                        <div class="card-body">
                            <h5>Perpustakaan</h5>
                            <p class="text-muted">Perpustakaan untuk para siswa/i membaca segala buku.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mekanisme Pendaftaran -->
    <section id="pendaftaran" class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="fw-bold">Alur Pendaftaran</h2>
                    <p class="lead">Ikuti langkah-langkah mudah untuk mendaftar</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center">
                        <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; margin-bottom: 20px;">
                            <h3 class="mb-0">1</h3>
                        </div>
                        <h5>Registrasi Akun</h5>
                        <p>Daftar akun dengan email dan password yang valid</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center">
                        <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; margin-bottom: 20px;">
                            <h3 class="mb-0">2</h3>
                        </div>
                        <h5>Isi Formulir</h5>
                        <p>Lengkapi biodata, data orang tua, dan asal sekolah</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center">
                        <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; margin-bottom: 20px;">
                            <h3 class="mb-0">3</h3>
                        </div>
                        <h5>Upload Berkas</h5>
                        <p>Upload dokumen persyaratan yang diperlukan</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="text-center">
                        <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; margin-bottom: 20px;">
                            <h3 class="mb-0">4</h3>
                        </div>
                        <h5>Pembayaran</h5>
                        <p>Lakukan pembayaran dan upload bukti transfer</p>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button class="btn btn-light btn-lg open-register">
                        <i class="fas fa-user-plus me-2"></i>Mulai Pendaftaran Sekarang
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h5>SMK Bakti Nusantara 666</h5>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Jl. Raya Percobaan No.65 Cileunyi, Bandung Satu, Jawa Barat, Indonesia 40393</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i>0812-3456-7890</p>
                    <p class="mb-2"><i class="fas fa-envelope me-2"></i>snpmbsmkbaktinusantara666@gmail.com</p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <p class="mb-0">&copy; 2025 SMK Bakti Nusantara 666. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Auth Modal -->
    <div id="authModal">
        <div style="display:flex;align-items:center;justify-content:center;height:100%;">
            <div style="background:linear-gradient(to right,#e2e2e2,#c9d6ff);display:flex;align-items:center;justify-content:center;width:100%;height:100%;">
                <div id="container">
                    <button id="authClose" onclick="closeModal()" style="position:absolute;top:15px;right:20px;background:none;border:none;font-size:24px;color:#999;cursor:pointer;z-index:1001;">&times;</button>
                    
                    <!-- Register Form -->
                    <div class="form-container sign-up">
                        <form id="registerForm" action="{{ route('register.post') }}" method="POST" style="background:#fff;display:flex;align-items:center;justify-content:center;flex-direction:column;padding:0 40px;height:100%;overflow-y:auto;">
                            @csrf
                            <h1 style="margin-bottom:15px;font-size:24px;">Buat Akun</h1>
                            <span style="font-size:11px;margin-bottom:10px;">Gunakan email untuk registrasi</span>
                            
                            <div id="step1" style="width:100%;">
                                <input type="text" id="regName" name="name" placeholder="Nama" required style="background:#eee;border:none;margin:6px 0;padding:10px 15px;font-size:13px;border-radius:8px;width:100%;outline:none;" />
                                <input type="email" id="regEmail" name="email" placeholder="Email" required style="background:#eee;border:none;margin:6px 0;padding:10px 15px;font-size:13px;border-radius:8px;width:100%;outline:none;" />
                                <div style="position:relative;width:100%;">
                                    <input type="password" id="regPassword" name="password" placeholder="Password" required style="background:#eee;border:none;margin:6px 0;padding:10px 40px 10px 15px;font-size:13px;border-radius:8px;width:100%;outline:none;" />
                                    <i class="fas fa-eye" onclick="togglePassword('regPassword', this)" style="position:absolute;right:15px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999;"></i>
                                </div>
                                <div style="position:relative;width:100%;">
                                    <input type="password" id="regPasswordConfirm" name="password_confirmation" placeholder="Konfirmasi Password" required style="background:#eee;border:none;margin:6px 0;padding:10px 40px 10px 15px;font-size:13px;border-radius:8px;width:100%;outline:none;" />
                                    <i class="fas fa-eye" onclick="togglePassword('regPasswordConfirm', this)" style="position:absolute;right:15px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999;"></i>
                                </div>
                                <button type="button" id="sendOtpBtn" style="background:#2da0a8;color:#fff;font-size:12px;padding:10px 45px;border:none;border-radius:8px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:8px;cursor:pointer;width:100%;">Kirim OTP</button>
                            </div>
                            
                            <div id="step2" style="width:100%;display:none;">
                                <p style="font-size:12px;margin:10px 0;text-align:center;">Kode OTP telah dikirim ke email Anda</p>
                                <p id="otpTimer" style="font-size:14px;margin:10px 0;text-align:center;color:#dc3545;font-weight:600;">Berlaku: 5:00</p>
                                <input type="text" id="otpCode" placeholder="Masukkan Kode OTP" maxlength="6" style="background:#eee;border:none;margin:8px 0;padding:10px 15px;font-size:13px;border-radius:8px;width:100%;outline:none;text-align:center;letter-spacing:5px;" />
                                <button type="button" id="verifyOtpBtn" style="background:#2da0a8;color:#fff;font-size:12px;padding:10px 45px;border:none;border-radius:8px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:8px;cursor:pointer;width:100%;">Verifikasi OTP</button>
                                <button type="button" id="resendOtpBtn" style="background:transparent;color:#2da0a8;font-size:11px;padding:8px;border:none;margin-top:5px;cursor:pointer;width:100%;" disabled>Kirim Ulang OTP (<span id="resendTimer">60</span>s)</button>
                            </div>
                            
                            <div id="step3" style="width:100%;display:none;">
                                <p style="font-size:12px;margin:10px 0;text-align:center;color:#28a745;"><i class="fas fa-check-circle"></i> OTP Terverifikasi!</p>
                                <input type="hidden" name="otp_verified" id="otpVerified" value="0" />
                                <button type="submit" id="finalRegisterBtn" style="background:#28a745;color:#fff;font-size:12px;padding:10px 45px;border:none;border-radius:8px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:8px;cursor:pointer;width:100%;">Daftar & Masuk Sekarang</button>
                            </div>
                            
                            <div id="regError" style="margin-top:10px;font-size:11px;padding:8px;border-radius:5px;display:none;width:100%;"></div>
                            <div id="regSuccess" style="margin-top:10px;font-size:11px;padding:8px;border-radius:5px;display:none;background:#d4edda;color:#155724;border:1px solid #c3e6cb;width:100%;"></div>
                        </form>
                    </div>
                    
                    <!-- Login Form -->
                    <div class="form-container sign-in">
                        <div id="loginForm" style="background:#fff;display:flex;align-items:center;justify-content:center;flex-direction:column;padding:0 40px;height:100%;">
                            <h1 style="margin-bottom:20px;">Masuk</h1>
                            <span style="font-size:12px;margin-bottom:15px;">Gunakan email dan password</span>
                            <input type="email" name="email" placeholder="Email" required style="background:#eee;border:none;margin:8px 0;padding:10px 15px;font-size:13px;border-radius:8px;width:100%;outline:none;" />
                            <div style="position:relative;width:100%;">
                                <input type="password" id="loginPassword" name="password" placeholder="Password" required style="background:#eee;border:none;margin:8px 0;padding:10px 40px 10px 15px;font-size:13px;border-radius:8px;width:100%;outline:none;" />
                                <i class="fas fa-eye" onclick="togglePassword('loginPassword', this)" style="position:absolute;right:15px;top:50%;transform:translateY(-50%);cursor:pointer;color:#999;"></i>
                            </div>
                            <a href="#" onclick="showForgotPassword(event)" style="color:#333;font-size:13px;text-decoration:none;margin:15px 0 10px;">Lupa Password?</a>
                            <button type="button" onclick="doLogin()" style="background:#2da0a8;color:#fff;font-size:12px;padding:10px 45px;border:none;border-radius:8px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:10px;cursor:pointer;">Masuk</button>
                            <div id="loginError" style="margin-top:10px;font-size:12px;padding:8px;border-radius:5px;display:none;"></div>
                            <div id="loginSuccess" style="margin-top:10px;font-size:12px;padding:8px;border-radius:5px;display:none;background:#d4edda;color:#155724;border:1px solid #c3e6cb;"></div>
                        </div>
                    </div>
                    
                    <!-- Toggle Panel -->
                    <div class="toggle-container">
                        <div class="toggle">
                            <div class="toggle-panel toggle-left">
                                <h1 style="color:#fff;">Selamat Datang!</h1>
                                <p style="color:#fff;margin:20px 0;">Silahkan login jika sudah memiliki akun</p>
                                <button id="showLogin" onclick="showLogin()" style="background:transparent;border:1px solid #fff;color:#fff;font-size:12px;padding:10px 45px;border-radius:8px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;cursor:pointer;">Login</button>
                            </div>
                            <div class="toggle-panel toggle-right">
                                <h1 style="color:#fff;">Selamat Datang!</h1>
                                <p style="color:#fff;margin:20px 0;">Silahkan daftar jika belum memiliki akun</p>
                                <button id="showRegister" onclick="showRegister()" style="background:transparent;border:1px solid #fff;color:#fff;font-size:12px;padding:10px 45px;border-radius:8px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;cursor:pointer;">Daftar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);z-index:10000;">
        <div style="display:flex;align-items:center;justify-content:center;height:100%;">
            <div style="background:#fff;padding:40px;border-radius:15px;max-width:450px;width:90%;position:relative;">
                <button onclick="closeForgotPassword()" style="position:absolute;top:15px;right:20px;background:none;border:none;font-size:24px;color:#999;cursor:pointer;">&times;</button>
                <h2 style="color:#2da0a8;margin-bottom:20px;text-align:center;">Lupa Password</h2>
                <p style="text-align:center;color:#666;margin-bottom:20px;font-size:14px;">Masukkan email Anda dan kami akan mengirimkan link untuk reset password</p>
                
                <div id="forgotMessage" style="display:none;padding:12px;border-radius:8px;margin-bottom:15px;font-size:13px;"></div>
                
                <form id="forgotPasswordForm">
                    <input type="email" id="forgotEmail" placeholder="Email" required style="background:#eee;border:none;margin:8px 0;padding:12px 15px;font-size:13px;border-radius:8px;width:100%;outline:none;" />
                    <button type="submit" style="background:#2da0a8;color:#fff;font-size:12px;padding:12px;border:none;border-radius:8px;font-weight:600;letter-spacing:0.5px;text-transform:uppercase;margin-top:10px;cursor:pointer;width:100%;">Kirim Link Reset</button>
                </form>
                
                <div style="text-align:center;margin-top:20px;">
                    <a href="#" onclick="closeForgotPassword();return false;" style="color:#2da0a8;text-decoration:none;font-size:13px;">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Card animations on scroll
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = `all 0.6s ease ${index * 0.1}s`;
        });
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });
        
        cards.forEach(card => observer.observe(card));
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('authModal');
            const container = document.getElementById('container');
            const closeBtn = document.getElementById('authClose');
            const showLogin = document.getElementById('showLogin');
            const showRegister = document.getElementById('showRegister');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            
            // Open modal - fix for all register buttons
            function openModal(e) {
                e.preventDefault();
                console.log('Modal opening...');
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
            
            // Find all buttons with open-register class
            const registerButtons = document.querySelectorAll('.open-register');
            console.log('Found register buttons:', registerButtons.length);
            
            registerButtons.forEach((btn, index) => {
                console.log('Adding listener to button', index, btn);
                btn.addEventListener('click', openModal);
            });
            
            // Alternative: use event delegation
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('open-register')) {
                    console.log('Button clicked via delegation');
                    openModal(e);
                }
            });
            
            // Also handle clicks on modal background
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Close modal
            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });
            
            // Toggle forms
            showRegister.addEventListener('click', () => {
                container.classList.add('active');
            });
            
            showLogin.addEventListener('click', () => {
                container.classList.remove('active');
            });
            
            // Login functionality moved to doLogin() function
            
            // OTP System
            const sendOtpBtn = document.getElementById('sendOtpBtn');
            const verifyOtpBtn = document.getElementById('verifyOtpBtn');
            const resendOtpBtn = document.getElementById('resendOtpBtn');
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');
            const errorDiv = document.getElementById('regError');
            const successDiv = document.getElementById('regSuccess');
            
            let userEmail = '';
            let otpTimerInterval = null;
            let resendTimerInterval = null;
            
            function startOtpTimer() {
                let timeLeft = 300; // 5 minutes in seconds
                const timerDisplay = document.getElementById('otpTimer');
                
                if (otpTimerInterval) clearInterval(otpTimerInterval);
                
                otpTimerInterval = setInterval(() => {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    timerDisplay.textContent = `Berlaku: ${minutes}:${seconds.toString().padStart(2, '0')}`;
                    
                    if (timeLeft <= 0) {
                        clearInterval(otpTimerInterval);
                        timerDisplay.textContent = 'OTP Kadaluarsa';
                        timerDisplay.style.color = '#999';
                        document.getElementById('verifyOtpBtn').disabled = true;
                    }
                    timeLeft--;
                }, 1000);
            }
            
            function startResendTimer() {
                let timeLeft = 60; // 60 seconds cooldown
                const resendBtn = document.getElementById('resendOtpBtn');
                const resendTimerSpan = document.getElementById('resendTimer');
                
                resendBtn.disabled = true;
                
                if (resendTimerInterval) clearInterval(resendTimerInterval);
                
                resendTimerInterval = setInterval(() => {
                    resendTimerSpan.textContent = timeLeft;
                    
                    if (timeLeft <= 0) {
                        clearInterval(resendTimerInterval);
                        resendBtn.disabled = false;
                        resendBtn.innerHTML = 'Kirim Ulang OTP';
                    }
                    timeLeft--;
                }, 1000);
            }
            
            sendOtpBtn.addEventListener('click', function() {
                const name = document.getElementById('regName').value;
                const email = document.getElementById('regEmail').value;
                const password = document.getElementById('regPassword').value;
                const passwordConfirm = document.getElementById('regPasswordConfirm').value;
                
                if (!name || !email || !password || !passwordConfirm) {
                    errorDiv.textContent = 'Semua field harus diisi';
                    errorDiv.style.display = 'block';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.border = '1px solid #f5c6cb';
                    return;
                }
                
                if (password !== passwordConfirm) {
                    errorDiv.textContent = 'Password dan konfirmasi password tidak sama';
                    errorDiv.style.display = 'block';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.border = '1px solid #f5c6cb';
                    return;
                }
                
                userEmail = email;
                sendOtpBtn.disabled = true;
                sendOtpBtn.textContent = 'Mengirim...';
                errorDiv.style.display = 'none';
                
                fetch('/otp/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        step1.style.display = 'none';
                        step2.style.display = 'block';
                        successDiv.textContent = data.message;
                        successDiv.style.display = 'block';
                        startOtpTimer();
                        startResendTimer();
                    } else {
                        errorDiv.textContent = data.message;
                        errorDiv.style.display = 'block';
                        errorDiv.style.background = '#f8d7da';
                        errorDiv.style.color = '#721c24';
                        errorDiv.style.border = '1px solid #f5c6cb';
                    }
                })
                .catch(() => {
                    errorDiv.textContent = 'Gagal mengirim OTP';
                    errorDiv.style.display = 'block';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.border = '1px solid #f5c6cb';
                })
                .finally(() => {
                    sendOtpBtn.disabled = false;
                    sendOtpBtn.textContent = 'Kirim OTP';
                });
            });
            
            verifyOtpBtn.addEventListener('click', function() {
                const otpCode = document.getElementById('otpCode').value;
                
                if (!otpCode || otpCode.length !== 6) {
                    errorDiv.textContent = 'Masukkan kode OTP 6 digit';
                    errorDiv.style.display = 'block';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.border = '1px solid #f5c6cb';
                    return;
                }
                
                verifyOtpBtn.disabled = true;
                verifyOtpBtn.textContent = 'Memverifikasi...';
                errorDiv.style.display = 'none';
                successDiv.style.display = 'none';
                
                fetch('/otp/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: userEmail, code: otpCode })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (otpTimerInterval) clearInterval(otpTimerInterval);
                        if (resendTimerInterval) clearInterval(resendTimerInterval);
                        document.getElementById('otpVerified').value = '1';
                        step2.style.display = 'none';
                        step3.style.display = 'block';
                        successDiv.textContent = data.message;
                        successDiv.style.display = 'block';
                    } else {
                        errorDiv.textContent = data.message;
                        errorDiv.style.display = 'block';
                        errorDiv.style.background = '#f8d7da';
                        errorDiv.style.color = '#721c24';
                        errorDiv.style.border = '1px solid #f5c6cb';
                    }
                })
                .catch(() => {
                    errorDiv.textContent = 'Gagal memverifikasi OTP';
                    errorDiv.style.display = 'block';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.border = '1px solid #f5c6cb';
                })
                .finally(() => {
                    verifyOtpBtn.disabled = false;
                    verifyOtpBtn.textContent = 'Verifikasi OTP';
                });
            });
            
            resendOtpBtn.addEventListener('click', function() {
                const originalText = resendOtpBtn.innerHTML;
                resendOtpBtn.disabled = true;
                resendOtpBtn.innerHTML = 'Mengirim ulang...';
                
                fetch('/otp/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: userEmail })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        successDiv.textContent = 'OTP berhasil dikirim ulang';
                        successDiv.style.display = 'block';
                        errorDiv.style.display = 'none';
                        document.getElementById('otpTimer').style.color = '#dc3545';
                        document.getElementById('verifyOtpBtn').disabled = false;
                        startOtpTimer();
                        startResendTimer();
                    }
                })
                .catch(() => {
                    resendOtpBtn.disabled = false;
                    resendOtpBtn.innerHTML = originalText;
                });
            });
            
            // Register form submit
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(registerForm);
                const submitBtn = document.getElementById('finalRegisterBtn');
                
                if (formData.get('otp_verified') !== '1') {
                    errorDiv.textContent = 'Silakan verifikasi OTP terlebih dahulu';
                    errorDiv.style.display = 'block';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.border = '1px solid #f5c6cb';
                    return;
                }
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'Memproses...';
                
                fetch('/register', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.redirect) {
                        successDiv.textContent = 'Registrasi berhasil! Mengalihkan...';
                        successDiv.style.display = 'block';
                        errorDiv.style.display = 'none';
                        setTimeout(() => { window.location.href = data.redirect; }, 1500);
                    } else if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('<br>');
                        errorDiv.innerHTML = errorMessages;
                        errorDiv.style.display = 'block';
                        errorDiv.style.background = '#f8d7da';
                        errorDiv.style.color = '#721c24';
                        errorDiv.style.border = '1px solid #f5c6cb';
                        successDiv.style.display = 'none';
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    errorDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    errorDiv.style.display = 'block';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.border = '1px solid #f5c6cb';
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Masuk Sekarang!';
                });
            });
        });
    </script>
    
    <script>
        function openModal() {
            document.getElementById('authModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('authModal').style.display = 'none';
        }
        
        function showRegister() {
            document.getElementById('container').classList.add('active');
        }
        
        function showLogin() {
            document.getElementById('container').classList.remove('active');
        }
        
        function doLogin() {
            const email = document.querySelector('#loginForm input[name="email"]').value;
            const password = document.querySelector('#loginForm input[name="password"]').value;
            const errorDiv = document.getElementById('loginError');
            const successDiv = document.getElementById('loginSuccess');
            
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';
            
            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    successDiv.textContent = 'Login berhasil! Mengalihkan...';
                    successDiv.style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1000);
                } else {
                    errorDiv.innerHTML = data.message || 'Email atau password salah!';
                    errorDiv.style.display = 'block';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.border = '1px solid #f5c6cb';
                }
            })
            .catch(error => {
                errorDiv.innerHTML = 'Terjadi kesalahan. Silakan coba lagi.';
                errorDiv.style.display = 'block';
                errorDiv.style.background = '#f8d7da';
                errorDiv.style.color = '#721c24';
                errorDiv.style.border = '1px solid #f5c6cb';
            });
        }
        
        function showForgotPassword(e) {
            e.preventDefault();
            document.getElementById('authModal').style.display = 'none';
            document.getElementById('forgotPasswordModal').style.display = 'block';
        }
        
        function closeForgotPassword() {
            document.getElementById('forgotPasswordModal').style.display = 'none';
            document.getElementById('authModal').style.display = 'block';
        }
        
        document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('forgotEmail').value;
            const messageDiv = document.getElementById('forgotMessage');
            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';
            messageDiv.style.display = 'none';
            
            try {
                const response = await fetch('/forgot-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    messageDiv.style.background = '#d4edda';
                    messageDiv.style.color = '#155724';
                    messageDiv.style.border = '1px solid #c3e6cb';
                    messageDiv.textContent = result.message;
                    messageDiv.style.display = 'block';
                    document.getElementById('forgotEmail').value = '';
                } else {
                    messageDiv.style.background = '#f8d7da';
                    messageDiv.style.color = '#721c24';
                    messageDiv.style.border = '1px solid #f5c6cb';
                    messageDiv.textContent = result.message || 'Email belum terdaftar';
                    messageDiv.style.display = 'block';
                }
            } catch (error) {
                messageDiv.style.background = '#f8d7da';
                messageDiv.style.color = '#721c24';
                messageDiv.style.border = '1px solid #f5c6cb';
                messageDiv.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                messageDiv.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kirim Link Reset';
            }
        });
        
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>