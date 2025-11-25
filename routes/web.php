<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {
    if ($request->session()->has('user')) {
        $user = $request->session()->get('user');
        
        switch ($user['role']) {
            case 'admin':
                return redirect()->route('admin.master');
            case 'panitia':
                return redirect()->route('panitia.dashboard');
            case 'keuangan':
                return redirect()->route('keuangan.dashboard');
            case 'kepala_sekolah':
                return redirect()->route('kepala-sekolah.dashboard');
            case 'pendaftar':
            case 'siswa':
                return redirect()->route('pendaftar.dashboard');
        }
    }
    
    return view('welcome');
})->name('welcome');

Route::get('/login', function () {
    return redirect('/');
})->name('login');

Route::post('/login', function(\Illuminate\Http\Request $request) {
    $result = app(App\Http\Controllers\Auth\LoginController::class)->login($request);
    if ($result->getStatusCode() === 200 && $request->session()->has('user')) {
        $user = $request->session()->get('user');
        \App\Helpers\AuditLogger::log('Login', 'User', $user['id'], ['email' => $user['email'], 'role' => $user['role']]);
    }
    return $result;
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->name('login.post');

// Forgot Password Routes
Route::post('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'sendResetLink'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->name('password.email');
Route::get('/reset-password', [App\Http\Controllers\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\ForgotPasswordController::class, 'resetPassword'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->name('password.update');

Route::post('/otp/send', [App\Http\Controllers\OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [App\Http\Controllers\OtpController::class, 'verify'])->name('otp.verify');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'otp_verified' => 'required|in:1'
        ]);
        
        // Verifikasi OTP sudah valid (cek is_verified = true)
        $otp = \App\Models\Otp::where('email', $request->email)
            ->where('is_verified', true)
            ->first();
        
        if (!$otp) {
            return response()->json([
                'success' => false,
                'errors' => ['otp' => ['Silakan verifikasi OTP terlebih dahulu']]
            ], 422);
        }
        
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'pendaftar',
            'email_verified_at' => now()
        ]);
        
        // Hapus semua OTP untuk email ini setelah berhasil register
        \App\Models\Otp::where('email', $request->email)->delete();
        
        $request->session()->put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ]);
        
        // Set flag untuk user baru register
        $request->session()->put('is_new_registration', true);
        
        // Audit Log
        \App\Helpers\AuditLogger::log('Register', 'User', $user->id, ['email' => $user->email]);
        
        return response()->json([
            'success' => true,
            'redirect' => route('pendaftar.form.first-time')
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    }
})->name('register.post');

Route::get('/admin/master', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    
    // Statistik utama
    $totalPendaftar = \App\Models\Pendaftar::count();
    $jurusanCount = \App\Models\Jurusan::count();
    $gelombangCount = \App\Models\Gelombang::count();
    $usersCount = \App\Models\User::count();
    
    // Statistik berdasarkan status pendaftar
    $diterima = \App\Models\Pendaftar::where('status', 'ACCEPTED')->count();
    $ditolak = \App\Models\Pendaftar::whereIn('status', ['ADM_REJECT', 'REJECTED'])->count();
    $pending = \App\Models\Pendaftar::whereIn('status', ['SUBMIT', 'REVISION_REQUIRED'])->count();
    $verified = \App\Models\Pendaftar::whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING', 'PAID'])->count();
    
    // Statistik tambahan
    $menungguPembayaran = \App\Models\Pendaftar::where('status', 'ADM_PASS')->count();
    $sudahBayar = \App\Models\Pendaftar::whereIn('status', ['PAID', 'ACCEPTED'])->count();
    $menungguVerifikasiPembayaran = \App\Models\Pendaftar::where('status', 'PAYMENT_PENDING')->count();
    
    $stats = [
        'jurusan' => $jurusanCount,
        'gelombang' => $gelombangCount,
        'total_pendaftar' => $totalPendaftar,
        'users' => $usersCount,
        'diterima' => $diterima,
        'ditolak' => $ditolak,
        'pending' => $pending,
        'verified' => $verified,
        'menunggu_pembayaran' => $menungguPembayaran,
        'sudah_bayar' => $sudahBayar,
        'menunggu_verifikasi_pembayaran' => $menungguVerifikasiPembayaran
    ];
    
    return view('admin.master.dashboard', compact('stats', 'mockUser'));
})->middleware([App\Http\Middleware\CheckSession::class . ':admin'])->name('admin.master');

Route::get('/admin/jurusan', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $jurusan = \App\Models\Jurusan::withCount('pendaftar')->get();
    return view('admin.jurusan.index', compact('jurusan', 'mockUser'));
})->middleware([App\Http\Middleware\CheckSession::class . ':admin'])->name('admin.jurusan');

Route::get('/admin/jurusan/{id}/edit', function (\Illuminate\Http\Request $request, $id) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $jurusan = \App\Models\Jurusan::findOrFail($id);
    return view('admin.jurusan.edit', compact('jurusan', 'mockUser'));
})->middleware([App\Http\Middleware\CheckSession::class . ':admin'])->name('jurusan.edit');

Route::get('/admin/gelombang', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $gelombang = \App\Models\Gelombang::all();
    return view('admin.gelombang.index', compact('gelombang', 'mockUser'));
})->middleware([App\Http\Middleware\CheckSession::class . ':admin'])->name('admin.gelombang');

Route::get('/admin/gelombang/{id}/edit', function (\Illuminate\Http\Request $request, $id) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $gelombang = \App\Models\Gelombang::findOrFail($id);
    return view('admin.gelombang.edit', compact('gelombang', 'mockUser'));
})->middleware([App\Http\Middleware\CheckSession::class . ':admin'])->name('gelombang.edit');

Route::get('/admin/users', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $users = \App\Models\User::all();
    $roles = ['admin', 'panitia', 'keuangan', 'kepala_sekolah', 'pendaftar'];
    return view('admin.master.users', compact('users', 'mockUser', 'roles'));
})->middleware([App\Http\Middleware\CheckSession::class . ':admin'])->name('admin.users');

Route::get('/admin/pendaftar', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'berkas'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    return view('admin.pendaftar.index', compact('pendaftar', 'mockUser'));
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('admin.pendaftar');

Route::get('/admin/pendaftar/{id}', function (\Illuminate\Http\Request $request, $id) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'gelombang', 'dataSiswa', 'berkas'])->findOrFail($id);
    return view('admin.pendaftar.show', compact('pendaftar', 'mockUser'));
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('admin.pendaftar.show');

Route::put('/admin/pendaftar/{id}/update-status', function (\Illuminate\Http\Request $request, $id) {
    $request->validate([
        'status' => 'required|string|in:SUBMIT,ADM_PASS,ADM_REJECT,PAID,ACCEPTED,REJECTED',
        'catatan' => 'nullable|string'
    ]);
    
    $pendaftar = \App\Models\Pendaftar::findOrFail($id);
    $pendaftar->update([
        'status' => $request->status
    ]);
    
    return redirect()->route('admin.pendaftar')->with('success', 'Status pendaftar berhasil diupdate');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('admin.pendaftar.update-status');

Route::get('/admin/verifikasi', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $verifikasi = collect([
        (object) ['id' => 1, 'nama' => 'John Doe', 'berkas' => 'Ijazah', 'status' => 'pending'],
        (object) ['id' => 2, 'nama' => 'Jane Smith', 'berkas' => 'Transkrip', 'status' => 'verified']
    ]);
    return view('admin.verifikasi.index', compact('verifikasi', 'mockUser'));
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('admin.verifikasi');

Route::get('/admin/audit-log', function (\Illuminate\Http\Request $request) {
    try {
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        
        $query = \App\Models\LogAktivitas::with('user')->orderBy('created_at', 'desc');
        
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->tanggal) {
            $query->whereDate('created_at', $request->tanggal);
        }
        
        $logs = $query->limit(50)->get();
        $users = \App\Models\User::orderBy('name')->get();
        
        return view('admin.audit-log', compact('logs', 'users', 'mockUser'));
    } catch (\Exception $e) {
        \Log::error('Error in audit-log: ' . $e->getMessage());
        
        $user = $request->session()->get('user');
        $mockUser = (object) $user;
        $logs = collect([]);
        $users = \App\Models\User::orderBy('name')->get();
        
        return view('admin.audit-log', compact('logs', 'users', 'mockUser'))
            ->with('error', 'Terjadi kesalahan saat memuat data audit log.');
    }
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('admin.audit-log');

Route::get('/panitia/dashboard', function (\Illuminate\Http\Request $request) {
    $totalPendaftar = \App\Models\Pendaftar::count();
    $menungguVerifikasi = \App\Models\Pendaftar::whereIn('status', ['SUBMIT', 'REVISION_REQUIRED'])->count();
    $berkasVerified = \App\Models\Pendaftar::whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING', 'PAID', 'ACCEPTED'])->count();
    $berkasDitolak = \App\Models\Pendaftar::whereIn('status', ['ADM_REJECT', 'REJECTED'])->count();
    $allPendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'berkas'])->paginate(10);
    
    return view('panitia.dashboard-simple', compact(
        'totalPendaftar', 'menungguVerifikasi', 'berkasVerified', 
        'berkasDitolak', 'allPendaftar'
    ));
})->middleware(App\Http\Middleware\CheckSession::class . ':panitia')->name('panitia.dashboard');

Route::get('/panitia/verifikasi', function (\Illuminate\Http\Request $request) {
    $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'berkas' => function($query) {
            $query->where('is_draft', false);
        }])
        ->whereIn('status', ['SUBMIT', 'REVISION_REQUIRED'])
        ->whereHas('berkas', function($query) {
            $query->where('is_draft', false);
        })
        ->paginate(10);
    return view('panitia.verifikasi.index', compact('pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':panitia')->name('panitia.verifikasi');

Route::get('/panitia/verifikasi/{id}', function (\Illuminate\Http\Request $request, $id) {
    $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'berkas' => function($query) {
            $query->where('is_draft', false);
        }])->findOrFail($id);
    return view('panitia.verifikasi.show', compact('pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':panitia')->name('panitia.verifikasi.show');

Route::post('/panitia/verifikasi/{id}/update-status', function (\Illuminate\Http\Request $request, $id) {
    $request->validate([
        'status' => 'required|in:ADM_PASS,ADM_REJECT'
    ]);
    
    $pendaftar = \App\Models\Pendaftar::with(['user', 'dataSiswa', 'gelombang'])->findOrFail($id);
    $user = $request->session()->get('user');
    
    $pendaftar->update([
        'status' => $request->status
    ]);
    
    try {
        $pendaftar->user->notify(new \App\Notifications\BerkasVerifiedNotification($pendaftar, $request->status));
    } catch (\Exception $e) {
        \Log::error('Gagal kirim notifikasi: ' . $e->getMessage());
    }
    
    // Audit Log
    \App\Helpers\AuditLogger::log('Verifikasi Berkas', 'Pendaftar', $pendaftar->id, [
        'no_pendaftaran' => $pendaftar->no_pendaftaran,
        'status' => $request->status,
        'hasil' => $request->status === 'ADM_PASS' ? 'Diterima' : 'Ditolak'
    ]);
    
    $message = $request->status === 'ADM_PASS' ? 'Berkas berhasil diterima!' : 'Berkas berhasil ditolak!';
    
    return redirect()->route('panitia.verifikasi')->with('success', $message);
})->middleware(App\Http\Middleware\CheckSession::class . ':panitia')->name('panitia.verifikasi.update-status');

Route::post('/panitia/berkas/{id}/verifikasi', function (\Illuminate\Http\Request $request, $id) {
    $request->validate([
        'status' => 'required|in:valid,invalid',
        'catatan' => 'nullable|string'
    ]);
    
    $berkas = \App\Models\PendaftarBerkas::findOrFail($id);
    
    if ($request->status === 'valid') {
        $berkas->update(['valid' => true, 'catatan' => null]);
        $message = 'Berkas berhasil diverifikasi!';
    } else {
        $berkas->update(['valid' => false, 'catatan' => $request->catatan]);
        
        // Ambil pendaftar dengan fresh data berkas
        $pendaftar = \App\Models\Pendaftar::with(['user', 'dataSiswa'])->findOrFail($berkas->pendaftar_id);
        
        // Update status pendaftar menjadi REVISION_REQUIRED
        $pendaftar->update(['status' => 'REVISION_REQUIRED']);
        
        // Ambil berkas yang ditolak (fresh query)
        $berkasYangDitolak = \App\Models\PendaftarBerkas::where('pendaftar_id', $pendaftar->id)
            ->where('valid', false)
            ->whereNotNull('catatan')
            ->get();
        
        // Kirim notifikasi email dengan detail berkas yang ditolak
        try {
            $pendaftar->user->notify(new \App\Notifications\BerkasRevisionNotification($pendaftar, $berkasYangDitolak));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim notifikasi: ' . $e->getMessage());
        }
        
        $message = 'Berkas ditolak dan notifikasi revisi telah dikirim!';
    }
    
    return back()->with('success', $message);
})->middleware(App\Http\Middleware\CheckSession::class . ':panitia')->name('panitia.berkas.verifikasi');

Route::get('/keuangan/dashboard', function (\Illuminate\Http\Request $request) {
    // Ambil data berdasarkan status pendaftar
    $totalPendaftar = \App\Models\Pendaftar::count();
    $sudahBayar = \App\Models\Pendaftar::whereIn('status', ['PAID', 'ACCEPTED'])->count();
    $menungguVerifikasi = \App\Models\Pendaftar::where('status', 'PAYMENT_PENDING')->count();
    $menungguPembayaran = \App\Models\Pendaftar::where('status', 'ADM_PASS')->count();
    $belumVerifikasi = \App\Models\Pendaftar::where('status', 'SUBMIT')->count();
    
    // Pendaftar yang menunggu verifikasi pembayaran dengan bukti pembayaran
    $pendaftarMenungguVerifikasi = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'gelombang', 'pembayaran'])
        ->where('status', 'PAYMENT_PENDING')
        ->latest()
        ->get();
    
    // Pendaftar yang sudah lulus administrasi tapi belum bayar
    $pendaftarMenungguBayar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'gelombang'])
        ->where('status', 'ADM_PASS')
        ->latest()
        ->get();
    
    // Semua pendaftar dengan status pembayaran
    $semuaPendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'gelombang'])
        ->latest()
        ->paginate(10);
    
    // Total pembayaran yang udah masuk 
    $totalEstimasiBayar = \App\Models\Pendaftar::join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
        ->whereIn('pendaftar.status', ['PAID', 'ACCEPTED'])
        ->sum('gelombang.biaya_daftar');
    
    // Total estimasi semua pendaftar 
    $totalEstimasiSemua = \App\Models\Pendaftar::join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
        ->sum('gelombang.biaya_daftar');
    
    // Estimasi yang belum terbayar
    $estimasiBelumBayar = \App\Models\Pendaftar::join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
        ->whereNotIn('pendaftar.status', ['PAID', 'ACCEPTED'])
        ->sum('gelombang.biaya_daftar');
    
    return view('keuangan.dashboard', compact(
        'totalPendaftar', 'sudahBayar', 'menungguVerifikasi', 'menungguPembayaran', 'belumVerifikasi',
        'pendaftarMenungguVerifikasi', 'pendaftarMenungguBayar', 'semuaPendaftar', 'totalEstimasiBayar',
        'totalEstimasiSemua', 'estimasiBelumBayar'
    ));
})->middleware(App\Http\Middleware\CheckSession::class . ':keuangan')->name('keuangan.dashboard');

Route::get('/keuangan/pembayaran/{id}', function (\Illuminate\Http\Request $request, $id) {
    $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'gelombang', 'pembayaran'])->findOrFail($id);
    return view('keuangan.pembayaran-detail', compact('pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':keuangan')->name('keuangan.pembayaran.detail');

Route::post('/keuangan/verifikasi/{id}', function (\Illuminate\Http\Request $request, $id) {
    $request->validate([
        'status' => 'required|in:PAID,ADM_PASS',
        'catatan' => 'nullable|string'
    ]);
    
    $pendaftar = \App\Models\Pendaftar::with(['user', 'dataSiswa', 'jurusan'])->findOrFail($id);
    $user = $request->session()->get('user');
    
    // Update status pembayaran
    $pembayaran = \App\Models\PendaftarPembayaran::where('pendaftar_id', $pendaftar->id)
        ->where('status', 'PENDING')
        ->first();
    
    if ($pembayaran) {
        $pembayaran->update([
            'status' => $request->status === 'PAID' ? 'VERIFIED' : 'REJECTED',
            'catatan' => $request->catatan
        ]);
    }
    
    // Auto-accept jika pembayaran terverifikasi dan berkas sudah lulus administrasi
    if ($request->status === 'PAID') {
        // Cek apakah sudah lulus administrasi (ADM_PASS -> PAYMENT_PENDING -> PAID)
        if ($pendaftar->status === 'PAYMENT_PENDING') {
            $pendaftar->update(['status' => 'ACCEPTED']);
            try {
                $pendaftar->user->notify(new \App\Notifications\PembayaranVerifiedNotification($pendaftar, 'ACCEPTED'));
            } catch (\Exception $e) {
                \Log::error('Gagal kirim notifikasi: ' . $e->getMessage());
            }
        }
    } else {
        // Jika pembayaran ditolak, kembali ke ADM_PASS
        $pendaftar->update(['status' => 'ADM_PASS']);
        try {
            $pendaftar->user->notify(new \App\Notifications\PembayaranVerifiedNotification($pendaftar, 'REJECTED'));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim notifikasi: ' . $e->getMessage());
        }
    }
    
    // Audit Log
    \App\Helpers\AuditLogger::log('Verifikasi Pembayaran', 'Pendaftar', $pendaftar->id, [
        'no_pendaftaran' => $pendaftar->no_pendaftaran,
        'status' => $request->status,
        'hasil' => $request->status === 'PAID' ? 'Diterima' : 'Ditolak'
    ]);
    
    return redirect()->route('keuangan.dashboard')->with('success', 'Status pembayaran berhasil diupdate');
})->middleware(App\Http\Middleware\CheckSession::class . ':keuangan')->name('keuangan.verifikasi');

Route::get('/kepala-sekolah/dashboard', [App\Http\Controllers\KepalaSekolah\DashboardController::class, 'index'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah')
    ->name('kepala-sekolah.dashboard');

Route::get('/kepala-sekolah/pendaftar', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'asalSekolah'])->paginate(15);
    return view('kepala-sekolah.pendaftar', compact('mockUser', 'pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah')->name('kepala-sekolah.pendaftar');

Route::get('/kepala-sekolah/diterima', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $mockUser = (object) $user;
    $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'dataSiswa', 'asalSekolah'])
        ->where('status', 'ACCEPTED')
        ->paginate(15);
    return view('kepala-sekolah.diterima', compact('mockUser', 'pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah')->name('kepala-sekolah.diterima');

Route::get('/kepala-sekolah/pembayaran', [App\Http\Controllers\KepalaSekolahController::class, 'rekapPembayaran'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah')
    ->name('kepala-sekolah.pembayaran');

Route::get('/kepala-sekolah/asal-sekolah', [App\Http\Controllers\KepalaSekolahController::class, 'dataAsalSekolah'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah')
    ->name('kepala-sekolah.asal-sekolah');

Route::get('/kepala-sekolah/asal-wilayah', [App\Http\Controllers\KepalaSekolah\DashboardController::class, 'asalWilayah'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah')
    ->name('kepala-sekolah.asal-wilayah');

Route::get('/pendaftar/dashboard', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    
    // Cek apakah user baru register
    if ($request->session()->has('is_new_registration')) {
        return redirect()->route('pendaftar.form.first-time');
    }
    
    // Cek apakah user sudah punya data pendaftar
    $pendaftar = \App\Models\Pendaftar::with(['dataSiswa', 'dataOrtu', 'asalSekolah', 'jurusan'])->where('user_id', $user['id'])->first();
    
    // Jika belum ada data pendaftar, redirect ke form first time
    if (!$pendaftar || !$pendaftar->dataSiswa) {
        return redirect()->route('pendaftar.form.first-time');
    }
    
    // Jika sudah ada data, tampilkan dashboard
    $pendaftar->load(['dataOrtu', 'asalSekolah']);
    return view('pendaftar.sidebar-dashboard', compact('pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')->name('pendaftar.dashboard');

Route::get('/pendaftar/form/first-time', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    
    // Cek apakah sudah punya data pendaftar
    $pendaftar = \App\Models\Pendaftar::with(['dataSiswa'])->where('user_id', $user['id'])->first();
    if ($pendaftar && $pendaftar->dataSiswa) {
        return redirect()->route('pendaftar.dashboard');
    }
    
    $jurusan = \App\Models\Jurusan::all();
    $gelombang = \App\Models\Gelombang::where('tgl_mulai', '<=', now())
        ->where('tgl_selesai', '>=', now())
        ->first();
    
    return view('pendaftar.form-first-time', compact('jurusan', 'gelombang'));
})->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')->name('pendaftar.form.first-time');

Route::get('/pendaftar/form', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    
    $pendaftar = \App\Models\Pendaftar::with(['dataSiswa', 'dataOrtu', 'asalSekolah', 'jurusan'])->where('user_id', $user['id'])->first();
    $jurusan = \App\Models\Jurusan::all();
    $gelombang = \App\Models\Gelombang::where('tgl_mulai', '<=', now())
        ->where('tgl_selesai', '>=', now())
        ->first();
    
    return view('pendaftar.form', compact('jurusan', 'gelombang', 'pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')->name('pendaftar.form');

Route::post('/pendaftar/form/store', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    
    $request->validate([
        'jurusan_id' => 'required|exists:jurusan,id',
        'nama' => 'required|string|max:255',
        'nisn' => 'required|string|max:10',
        'jenis_kelamin' => 'required|in:L,P',
        'tempat_lahir' => 'required|string|max:100',
        'tgl_lahir' => 'required|date',
        'alamat' => 'required|string',
        'npsn' => 'nullable|string|max:20',
        'nama_sekolah' => 'required|string|max:150',
        'kabupaten_sekolah' => 'nullable|string|max:100',
        'nilai_rata' => 'nullable|numeric|min:0|max:100',
        'province_id' => 'required|exists:provinces,id',
        'regency_id' => 'required|exists:regencies,id',
        'district_id' => 'required|exists:districts,id',
        'village_id' => 'required|exists:villages,id',
        'nama_ayah' => 'nullable|string|max:120',
        'pekerjaan_ayah' => 'nullable|string|max:100',
        'hp_ayah' => 'nullable|string|min:10|max:13|regex:/^[0-9]+$/',
        'nama_ibu' => 'nullable|string|max:120',
        'pekerjaan_ibu' => 'nullable|string|max:100',
        'hp_ibu' => 'nullable|string|min:10|max:13|regex:/^[0-9]+$/',
        'wali_nama' => 'nullable|string|max:120',
        'wali_hp' => 'nullable|string|min:10|max:13|regex:/^[0-9]+$/'
    ]);
    
    $gelombang = \App\Models\Gelombang::where('tgl_mulai', '<=', now())
        ->where('tgl_selesai', '>=', now())
        ->first();
    
    // Cek apakah sudah ada data pendaftar
    $existingPendaftar = \App\Models\Pendaftar::where('user_id', $user['id'])->first();
    
    // Buat atau update data pendaftar
    $pendaftar = \App\Models\Pendaftar::updateOrCreate(
        ['user_id' => $user['id']],
        [
            'jurusan_id' => $request->jurusan_id,
            'gelombang_id' => $request->gelombang_id ?? $gelombang?->id,
            'status' => 'SUBMIT',
            'updated_at' => now()
        ]
    );
    
    // Generate nomor pendaftaran jika belum ada
    if (!$pendaftar->no_pendaftaran) {
        $tahun = date('Y');
        $urutan = str_pad($pendaftar->id, 5, '0', STR_PAD_LEFT);
        $pendaftar->update(['no_pendaftaran' => $tahun . $urutan]);
    }
    
    // Buat atau update data siswa
    \App\Models\PendaftarDataSiswa::updateOrCreate(
        ['pendaftar_id' => $pendaftar->id],
        [
            'nama' => $request->nama,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id
        ]
    );
    
    // Buat atau update data asal sekolah
    \App\Models\PendaftarAsalSekolah::updateOrCreate(
        ['pendaftar_id' => $pendaftar->id],
        [
            'npsn' => $request->npsn,
            'nama_sekolah' => $request->nama_sekolah,
            'kabupaten' => $request->kabupaten_sekolah,
            'nilai_rata' => $request->nilai_rata
        ]
    );
    
    // Buat atau update data orang tua
    \App\Models\PendaftarDataOrtu::updateOrCreate(
        ['pendaftar_id' => $pendaftar->id],
        [
            'nama_ayah' => $request->nama_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'hp_ayah' => $request->hp_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'hp_ibu' => $request->hp_ibu,
            'wali_nama' => $request->wali_nama,
            'wali_hp' => $request->wali_hp
        ]
    );
    
    if (!$existingPendaftar || $existingPendaftar->status !== 'SUBMIT') {
        try {
            $userModel = \App\Models\User::find($user['id']);
            $pendaftar->load(['dataSiswa', 'jurusan']);
            $userModel->notify(new \App\Notifications\PendaftaranSubmittedNotification($pendaftar));
        } catch (\Exception $e) {
            \Log::error('Gagal kirim notifikasi: ' . $e->getMessage());
        }
    }
    
    // Hapus flag new registration setelah submit form
    $request->session()->forget('is_new_registration');
    
    // Audit Log
    \App\Helpers\AuditLogger::log(
        $existingPendaftar ? 'Update Formulir Pendaftaran' : 'Submit Formulir Pendaftaran',
        'Pendaftar',
        $pendaftar->id,
        ['no_pendaftaran' => $pendaftar->no_pendaftaran, 'jurusan' => $pendaftar->jurusan->nama]
    );
    
    return redirect()->route('pendaftar.dashboard')->with('success', 'Data pendaftaran berhasil disimpan!');
})->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')->name('pendaftar.form.store');

Route::get('/pendaftar/berkas', [App\Http\Controllers\Pendaftar\BerkasController::class, 'index'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')
    ->name('pendaftar.berkas');

// ROUTE LAMA DIHAPUS - SEMUA UPLOAD HARUS MELALUI DRAFT SYSTEM
// Gunakan route 'pendaftar.berkas.auto-save' untuk menyimpan draft
// Gunakan route controller store() untuk finalisasi berkas

Route::post('/pendaftar/berkas/store', [App\Http\Controllers\Pendaftar\BerkasController::class, 'store'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')
    ->name('pendaftar.berkas.store');

Route::post('/pendaftar/berkas/auto-save', [App\Http\Controllers\Pendaftar\BerkasController::class, 'autoSave'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')
    ->name('pendaftar.berkas.auto-save');

Route::delete('/pendaftar/berkas/draft/{id}', [App\Http\Controllers\Pendaftar\BerkasController::class, 'deleteDraft'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')
    ->name('pendaftar.berkas.delete-draft');

Route::post('/pendaftar/berkas/upload-ulang', [App\Http\Controllers\Pendaftar\BerkasController::class, 'uploadUlang'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')
    ->name('pendaftar.berkas.upload-ulang');

Route::get('/pendaftar/status', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $pendaftar = \App\Models\Pendaftar::with(['dataSiswa', 'jurusan', 'gelombang', 'berkas' => function($query) {
        $query->orderBy('created_at', 'desc');
    }])->where('user_id', $user['id'])->first();
    
    return view('pendaftar.status', compact('pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')->name('pendaftar.status');

Route::get('/pendaftar/pembayaran', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $pendaftar = \App\Models\Pendaftar::with(['dataSiswa', 'jurusan', 'gelombang'])->where('user_id', $user['id'])->first();
    
    return view('pendaftar.pembayaran', compact('pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')->name('pendaftar.pembayaran');

Route::post('/pendaftar/pembayaran/store', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'bank_tujuan' => 'required|string',
        'nominal' => 'required|numeric',
        'tanggal_transfer' => 'required|date',
        'nama_pengirim' => 'required|string|max:255',
        'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
    ]);
    
    $user = $request->session()->get('user');
    $pendaftar = \App\Models\Pendaftar::where('user_id', $user['id'])->first();
    
    if (!$pendaftar || $pendaftar->status !== 'ADM_PASS') {
        return back()->with('error', 'Anda belum bisa melakukan pembayaran');
    }
    
    // Upload bukti transfer
    $file = $request->file('bukti_transfer');
    $fileName = $pendaftar->no_pendaftaran . '_bukti_transfer.' . $file->getClientOriginalExtension();
    $filePath = $file->storeAs('pembayaran', $fileName, 'public');
    
    // Simpan data pembayaran
    \App\Models\PendaftarPembayaran::create([
        'pendaftar_id' => $pendaftar->id,
        'bank_tujuan' => $request->bank_tujuan,
        'nominal' => $request->nominal,
        'tanggal_transfer' => $request->tanggal_transfer,
        'nama_pengirim' => $request->nama_pengirim,
        'bukti_transfer' => $fileName,
        'status' => 'PENDING'
    ]);
    
    // Update status ke PAYMENT_PENDING (menunggu verifikasi keuangan)
    $pendaftar->update(['status' => 'PAYMENT_PENDING']);
    
    // Audit Log
    \App\Helpers\AuditLogger::log('Upload Bukti Pembayaran', 'Pendaftar', $pendaftar->id, [
        'no_pendaftaran' => $pendaftar->no_pendaftaran,
        'nominal' => $request->nominal,
        'bank_tujuan' => $request->bank_tujuan
    ]);
    
    return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi dari bagian keuangan.');
})->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')->name('pendaftar.pembayaran.store');

Route::get('/pendaftar/cetak', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    $pendaftar = \App\Models\Pendaftar::with(['dataSiswa', 'jurusan', 'gelombang', 'user'])->where('user_id', $user['id'])->first();
    
    if (!$pendaftar) {
        return redirect()->route('pendaftar.dashboard')->with('error', 'Data pendaftar tidak ditemukan');
    }
    
    return view('pendaftar.cetak', compact('pendaftar'));
})->middleware(App\Http\Middleware\CheckSession::class . ':pendaftar')->name('pendaftar.cetak');

// Jurusan CRUD routes
Route::post('/admin/jurusan', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'kode' => 'required|string|max:10|unique:jurusan,kode',
        'nama' => 'required|string|max:100',
        'kuota' => 'required|integer|min:1'
    ]);
    
    \App\Models\Jurusan::create([
        'kode' => $request->kode,
        'nama' => $request->nama,
        'kuota' => $request->kuota
    ]);
    
    return redirect('/admin/jurusan')->with('success', 'Jurusan berhasil ditambahkan');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('jurusan.store');

Route::put('/admin/jurusan/{id}', function (\Illuminate\Http\Request $request, $id) {
    $request->validate([
        'kode' => 'required|string|max:10|unique:jurusan,kode,' . $id,
        'nama' => 'required|string|max:100',
        'kuota' => 'required|integer|min:1'
    ]);
    
    $jurusan = \App\Models\Jurusan::findOrFail($id);
    $jurusan->update([
        'kode' => $request->kode,
        'nama' => $request->nama,
        'kuota' => $request->kuota
    ]);
    
    return redirect('/admin/jurusan')->with('success', 'Jurusan berhasil diupdate');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('jurusan.update');

Route::delete('/admin/jurusan/{id}', function ($id) {
    \App\Models\Jurusan::findOrFail($id)->delete();
    return redirect('/admin/jurusan')->with('success', 'Jurusan berhasil dihapus');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('jurusan.destroy');

// Gelombang CRUD routes
Route::post('/admin/gelombang', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'nama' => 'required|string|max:255',
        'tahun' => 'required|integer|min:2020',
        'tgl_mulai' => 'required|date',
        'tgl_selesai' => 'required|date|after:tgl_mulai',
        'biaya_daftar' => 'required|numeric|min:0'
    ]);
    
    \App\Models\Gelombang::create([
        'nama' => $request->nama,
        'tahun' => $request->tahun,
        'tgl_mulai' => $request->tgl_mulai,
        'tgl_selesai' => $request->tgl_selesai,
        'biaya_daftar' => $request->biaya_daftar
    ]);
    
    return redirect('/admin/gelombang')->with('success', 'Gelombang berhasil ditambahkan');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('gelombang.store');

Route::put('/admin/gelombang/{id}', function (\Illuminate\Http\Request $request, $id) {
    $request->validate([
        'nama' => 'required|string|max:255',
        'tahun' => 'required|integer|min:2020',
        'tgl_mulai' => 'required|date',
        'tgl_selesai' => 'required|date|after:tgl_mulai',
        'biaya_daftar' => 'required|numeric|min:0'
    ]);
    
    $gelombang = \App\Models\Gelombang::findOrFail($id);
    $gelombang->update([
        'nama' => $request->nama,
        'tahun' => $request->tahun,
        'tgl_mulai' => $request->tgl_mulai,
        'tgl_selesai' => $request->tgl_selesai,
        'biaya_daftar' => $request->biaya_daftar
    ]);
    
    return redirect('/admin/gelombang')->with('success', 'Gelombang berhasil diupdate');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('gelombang.update');

Route::delete('/admin/gelombang/{id}', function ($id) {
    \App\Models\Gelombang::findOrFail($id)->delete();
    return redirect('/admin/gelombang')->with('success', 'Gelombang berhasil dihapus');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('gelombang.destroy');

// Users CRUD routes
Route::post('/admin/users', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|string|in:admin,panitia,keuangan,kepala_sekolah'
    ]);
    
    // Validasi: Admin tidak bisa membuat user dengan role pendaftar
    if ($request->role === 'pendaftar') {
        return redirect('/admin/users')->with('error', 'Role "Pendaftar" hanya bisa dibuat melalui registrasi publik dengan verifikasi OTP');
    }
    
    \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
        'email_verified_at' => now()
    ]);
    
    return redirect('/admin/users')->with('success', 'User berhasil ditambahkan');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('admin.users.store');

Route::put('/admin/users/{id}', function (\Illuminate\Http\Request $request, $id) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|string|in:admin,panitia,keuangan,kepala_sekolah,pendaftar'
    ]);
    
    $user = \App\Models\User::findOrFail($id);
    
    // Validasi: Tidak bisa mengubah role menjadi pendaftar
    if ($request->role === 'pendaftar' && $user->role !== 'pendaftar') {
        return redirect('/admin/users')->with('error', 'Tidak dapat mengubah role menjadi "Pendaftar"');
    }
    
    // Validasi: Tidak bisa mengubah role dari pendaftar ke role lain
    if ($user->role === 'pendaftar' && $request->role !== 'pendaftar') {
        return redirect('/admin/users')->with('error', 'Tidak dapat mengubah role "Pendaftar" ke role lain');
    }
    
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role
    ]);
    
    if ($request->password) {
        $user->update(['password' => bcrypt($request->password)]);
    }
    
    return redirect('/admin/users')->with('success', 'User berhasil diupdate');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('admin.users.update');

Route::delete('/admin/users/{id}', function ($id) {
    \App\Models\User::findOrFail($id)->delete();
    return redirect('/admin/users')->with('success', 'User berhasil dihapus');
})->middleware(App\Http\Middleware\CheckSession::class . ':admin')->name('admin.users.delete');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    $user = $request->session()->get('user');
    if ($user) {
        \App\Helpers\AuditLogger::log('Logout', 'User', $user['id'], ['email' => $user['email']]);
    }
    $request->session()->forget('user');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/')->with('success', 'Berhasil logout');
})->name('logout');

Route::get('/logout', function (\Illuminate\Http\Request $request) {
    $request->session()->forget('user');
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

// API Routes for Wilayah
Route::get('/api/provinces', function() {
    return response()->json(\App\Models\Province::orderBy('name')->get());
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/api/regencies/{provinceId}', function($provinceId) {
    return response()->json(\App\Models\Regency::where('province_id', $provinceId)->orderBy('name')->get());
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/api/districts/{regencyId}', function($regencyId) {
    return response()->json(\App\Models\District::where('regency_id', $regencyId)->orderBy('name')->get());
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/api/villages/{districtId}', function($districtId) {
    return response()->json(\App\Models\Village::where('district_id', $districtId)->orderBy('name')->get());
})->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Export Routes
Route::get('/export/pendaftar/excel', [App\Http\Controllers\KepalaSekolah\ExportController::class, 'excel'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah,admin')
    ->name('export.pendaftar.excel');

Route::get('/export/pendaftar/pdf', [App\Http\Controllers\KepalaSekolah\ExportController::class, 'pdf'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah,admin')
    ->name('export.pendaftar.pdf');

Route::get('/export/pembayaran/excel', [App\Http\Controllers\KepalaSekolah\ExportController::class, 'pembayaranExcel'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah,admin')
    ->name('export.pembayaran.excel');

Route::get('/export/pembayaran/pdf', [App\Http\Controllers\KepalaSekolah\ExportController::class, 'pembayaranPdf'])
    ->middleware(App\Http\Middleware\CheckSession::class . ':kepala_sekolah,admin')
    ->name('export.pembayaran.pdf');

// Testing Email Routes (Development Only)
if (config('app.env') === 'local') {
    Route::get('/test-email/pendaftaran', [App\Http\Controllers\TestEmailController::class, 'testPendaftaranEmail']);
    Route::get('/test-email/berkas/{status?}', [App\Http\Controllers\TestEmailController::class, 'testBerkasEmail']);
    Route::get('/test-email/revisi', [App\Http\Controllers\TestEmailController::class, 'testRevisiEmail']);
    Route::get('/test-email/pembayaran/{status?}', [App\Http\Controllers\TestEmailController::class, 'testPembayaranEmail']);
}
